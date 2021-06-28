<?php

namespace App\Http\Livewire\Department;

use App\Models\Department;
use Livewire\Component;

class DepartmentIndex extends Component
{
    public $search = '';
    public $name;
    public $editMode = false;
    public $departmentId;

    protected $rules = [
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->departmentId = $id;
        $this->loadDepartment();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'show']);
    }

    public function loadDepartment()
    {
        $department = Department::find($this->departmentId);
        $this->name = $department->name;
    }
    public function deleteDepartment($id)
    {
        $department = Department::find($id);
        $department->delete();
        session()->flash('department-message', 'Department successfully deleted');
    }
    public function showDepartmentModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'show']);
    }
    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
    }
    public function storeDepartment()
    {
        $this->validate();
        Department::create([
           'name'         => $this->name
       ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully created');
    }
    public function updateDepartment()
    {
        $validated = $this->validate([
            'name'        => 'required'
        ]);
        $department = Department::find($this->departmentId);
        $department->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully updated');
    }
    public function render()
    {
        $departments = Department::paginate(5);
        if (strlen($this->search) > 2) {
            $departments = Department::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.department.department-index', [
            'departments' => $departments
        ])->layout('layouts.main');
    }
}
