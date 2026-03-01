<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UsersImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $errors = [];
    private $companyNotFound = [];
    private $duplicateAction = null;
    private $results = [
        'success'           => 0,
        'skipped'           => 0,
        'failed'            => 0,
        'company_not_found' => 0,
    ];

    public function collection(Collection $rows)
    {
        $user          = Auth::user();
        $userCompanyId = $user->company_id;
        $isSuperadmin  = is_null($userCompanyId);
        $processedData = [];

        // Cache valid roles for performance
        $validRoles = Role::pluck('name', 'name')->toArray();

        foreach ($rows as $index => $row) {
            try {
                $rowData = $row->toArray();

                // Clean the data
                $name      = $this->cleanValue($row['name']       ?? '');
                $email     = $this->cleanValue($row['email']      ?? '');
                $password  = $this->cleanValue($row['password']   ?? '');
                $companyIdRaw = $this->cleanValue($row['company_id'] ?? null);
                $band      = $this->cleanValue($row['band']       ?? null);
                $rolesRaw  = $isSuperadmin
                    ? $this->cleanValue($row['roles'] ?? null)
                    : null;

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $name, $email)) {
                    continue;
                }

                // Skip if required fields are empty
                if (empty($name) || empty($email) || empty($password)) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Resolve company_id
                $companyId = null;
                if (!empty($companyIdRaw)) {
                    if (!is_numeric($companyIdRaw)) {
                        if ($this->isImportMode()) {
                            $this->results['failed']++;
                        }
                        continue;
                    }

                    $companyId = (int) $companyIdRaw;

                    // Non-superadmin can only use their own company_id
                    if (!$isSuperadmin && $companyId !== (int) $userCompanyId) {
                        if ($this->isImportMode()) {
                            $this->results['failed']++;
                        }
                        continue;
                    }

                    // Check company exists
                    if (!\App\Models\Company::where('id', $companyId)->exists()) {
                        if ($this->isImportMode()) {
                            $this->results['company_not_found']++;
                        }
                        if (!in_array($companyId, $this->companyNotFound)) {
                            $this->companyNotFound[] = $companyId;
                        }
                        continue;
                    }
                } elseif (!$isSuperadmin && !is_null($userCompanyId)) {
                    // Non-superadmin with no company_id in row → default to their company
                    $companyId = (int) $userCompanyId;
                }

                // Parse band
                $bandParsed = null;
                if (!is_null($band) && is_numeric($band)) {
                    $bandParsed = (int) $band;
                }

                // Parse roles (superadmin only)
                $roleNames = [];
                if ($isSuperadmin && !empty($rolesRaw)) {
                    $parsed = array_map('trim', explode(',', $rolesRaw));
                    foreach ($parsed as $roleName) {
                        if (!empty($roleName) && isset($validRoles[$roleName])) {
                            $roleNames[] = $roleName;
                        }
                    }
                }

                // Prepare user data
                $userData = [
                    'name'       => $name,
                    'email'      => strtolower(trim($email)),
                    'password'   => $password, // raw, validated then hashed
                    'company_id' => $companyId,
                    'band'       => $bandParsed,
                ];

                // Validate
                $existingUser = User::where('email', $userData['email'])->first();
                $isDuplicate  = !is_null($existingUser);

                $emailRule = $isDuplicate ? 'required|email|max:255' : 'required|email|max:255|unique:users,email';

                $validator = Validator::make($userData, [
                    'name'       => 'required|string|max:255',
                    'email'      => $emailRule,
                    'password'   => [
                        'required',
                        'string',
                        'min:12',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                    ],
                    'company_id' => 'nullable|integer|exists:companies,id',
                    'band'       => 'nullable|integer|min:1|max:5',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Handle import mode
                if ($this->isImportMode()) {
                    $saveData = [
                        'name'               => $userData['name'],
                        'email'              => $userData['email'],
                        'password'           => Hash::make($userData['password']),
                        'company_id'         => $userData['company_id'],
                        'band'               => $userData['band'],
                        'email_verified_at'  => now(),
                    ];

                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingUser->update($saveData);
                            if ($isSuperadmin) {
                                $existingUser->syncRoles($roleNames);
                            }
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        $newUser = User::create($saveData);
                        if ($isSuperadmin && !empty($roleNames)) {
                            $newUser->syncRoles($roleNames);
                        }
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = [
                        'name'         => $userData['name'],
                        'email'        => $userData['email'],
                        'company_id'   => $userData['company_id'],
                        'company_name' => $userData['company_id']
                            ? (\App\Models\Company::find($userData['company_id'])?->name ?? 'N/A')
                            : '-',
                        'band'         => $userData['band'],
                        'roles'        => $roleNames,
                        'is_duplicate' => $isDuplicate,
                        // Never expose raw password in preview
                        'password_set' => !empty($userData['password']),
                    ];
                    $processedData[] = $previewData;
                }
            } catch (\Exception $e) {
                if ($this->isImportMode()) {
                    $this->results['failed']++;
                }
                continue;
            }
        }

        if (!$this->isImportMode()) {
            $this->data = $processedData;
        }
    }

    private function shouldSkipRow(array $rowData, ?string $name, ?string $email): bool
    {
        $rawName  = strtoupper(trim($rowData['name']  ?? ''));
        $rawEmail = strtoupper(trim($rowData['email'] ?? ''));

        if (str_starts_with($rawName, 'CONTOH:') || str_starts_with($rawEmail, 'CONTOH:')) {
            return true;
        }

        $firstColumn = strtolower((string) $name);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'name') ||
            empty($firstColumn)
        ) {
            return true;
        }

        return false;
    }

    private function cleanValue(mixed $value): ?string
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        $value = (string) $value;

        $patterns = ['CONTOH: ', 'contoh: ', 'Contoh: ', 'CONTOH:', 'contoh:', 'Contoh:'];
        foreach ($patterns as $pattern) {
            if (str_starts_with($value, $pattern)) {
                $value = substr($value, strlen($pattern));
                break;
            }
        }

        return trim($value);
    }

    private function isImportMode(): bool
    {
        return !is_null($this->duplicateAction);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCompanyNotFound(): array
    {
        return array_unique($this->companyNotFound);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setDuplicateAction(string $action): static
    {
        $this->duplicateAction = in_array($action, ['skip', 'update']) ? $action : 'skip';
        return $this;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
