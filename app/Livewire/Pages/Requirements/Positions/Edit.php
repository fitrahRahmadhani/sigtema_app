<?php

namespace App\Livewire\Pages\Requirements\Positions;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Position;
use App\Models\PositionRequirementSetting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Position Requirements')]
class Edit extends Component
{
    use WithDeleteConfirmation, UnauthorizedAccess, HandleException;

    public Position $position;
    public array $requirements = [];
    public array $newRequirements = [
        ['name' => '', 'value' => '']
    ];

    public function mount(Position $position)
    {
        $this->position = $position;
        $this->loadRequirements();
    }

    private function loadRequirements()
    {
        $this->requirements = PositionRequirementSetting::where('position_id', $this->position->id)
            ->orderBy('id')
            ->get()
            ->map(fn($req) => [
                'id' => $req->id,
                'name' => $req->name,
                'value' => $req->value
            ])
            ->toArray();
    }

    protected function getModelClass(): string
    {
        return PositionRequirementSetting::class;
    }

    public function addNewRequirementRow()
    {
        $this->newRequirements[] = ['name' => '', 'value' => ''];
    }

    public function removeNewRequirementRow($index)
    {
        if (count($this->newRequirements) > 1) {
            unset($this->newRequirements[$index]);
            $this->newRequirements = array_values($this->newRequirements);
        }
    }

    public function updateRequirement($index)
    {
        if (!isset($this->requirements[$index]['id'])) {
            return;
        }

        $data = $this->requirements[$index];
        $name = trim($data['name'] ?? '');
        $value = trim($data['value'] ?? '');

        if (empty($name)) {
            Toaster::warning('Requirement name cannot be empty.');
            return;
        }

        try {
            $model = PositionRequirementSetting::findOrFail($data['id']);
            $this->authorize('update', $model);

            DB::beginTransaction();

            $model->update([
                'name' => $name,
                'value' => $value
            ]);

            DB::commit();

            Toaster::success('Requirement updated successfully.');
            $this->loadRequirements();
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('update requirement');
        } catch (\Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to update requirement.');
            return $this->handleException('update requirement', $e->getMessage());
        }
    }

    public function addRequirements()
    {
        $valid = [];

        foreach ($this->newRequirements as $index => $req) {
            $name = trim($req['name'] ?? '');
            $value = trim($req['value'] ?? '');

            if (empty($name) && empty($value)) {
                continue;
            }

            if (empty($name)) {
                Toaster::warning("Requirement name cannot be empty in row " . ($index + 1));
                return;
            }

            $valid[] = [
                'name' => $name,
                'value' => $value
            ];
        }

        if (empty($valid)) {
            Toaster::warning('Please add at least one requirement.');
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($valid as $req) {
                $model = new PositionRequirementSetting([
                    'position_id' => $this->position->id,
                    'position_code' => $this->position->position_code,
                    'name' => $req['name'],
                    'value' => $req['value']
                ]);

                $this->authorize('update', $model);
                $model->save();
            }

            DB::commit();

            $this->newRequirements = [['name' => '', 'value' => '']];
            $this->loadRequirements();

            Toaster::success(count($valid) . ' new requirements added successfully.');
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('add requirement');
        } catch (\Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to add requirements.');
            return $this->handleException('add requirement', $e->getMessage());
        }
    }

    protected function performDelete($item): void
    {
        try {
            $this->authorize('delete', $item);

            DB::beginTransaction();
            $item->delete();
            DB::commit();

            Toaster::success('Requirement deleted successfully.');
            $this->loadRequirements();
        } catch (AuthorizationException $e) {
            DB::rollBack();
            $this->unauthorizedAccess('delete requirement');
        } catch (\Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to delete requirement.');
            $this->handleException('delete requirement', $e->getMessage());
        }
    }

    public function save()
    {
        try {
            DB::beginTransaction();

            foreach ($this->requirements as $index => $req) {
                if (!isset($req['id'])) {
                    continue;
                }

                $name = trim($req['name'] ?? '');
                if (empty($name)) {
                    DB::rollBack();
                    Toaster::warning("Requirement name cannot be empty in row " . ($index + 1));
                    return;
                }

                $model = PositionRequirementSetting::findOrFail($req['id']);
                $this->authorize('update', $model);

                $model->update([
                    'name' => $name,
                    'value' => trim($req['value'] ?? '')
                ]);
            }

            DB::commit();

            Toaster::success('Requirements updated successfully.');
            return redirect()->route('requirements.position');
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('update requirement');
        } catch (\Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to update requirements.');
            return $this->handleException('update requirement', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('requirements.position');
    }

    public function render()
    {
        return view('livewire.pages.requirements.positions.edit');
    }
}
