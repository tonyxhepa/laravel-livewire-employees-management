<?php

namespace App\Http\Livewire\Employee;

use App\Models\Employee;
use Livewire\Component;

class EmployeeIndex extends Component
{
    public $search = '';
    public $lastName;
    public $firstName;
    public $middleName;
    public $address;
    public $countryId;
    public $stateId;
    public $cityId;
    public $departmentId;
    public $zipCode;
    public $birthDate;
    public $dateHired;
    public $editMode = false;
    public $employeeId;

    protected $rules = [
        'lastName' => 'required',
        'firstName' => 'required',
        'middleName' => 'required',
        'address' => 'required',
        'countryId' => 'required',
        'stateId' => 'required',
        'cityId' => 'required',
        'zipCode' => 'required',
        'birthDate' => 'required',
        'dateHired' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->stateId = $id;
        $this->loadStates();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'show']);
    }

    public function loadStates()
    {
        $state = Employee::find($this->stateId);
        $this->countryId = $state->country_id;
        $this->name = $state->name;
    }
    public function deleteState($id)
    {
        $state = Employee::find($id);
        $state->delete();
        session()->flash('state-message', 'State successfully deleted');
    }
    public function showEmployeeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'show']);
    }
    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
    }
    public function storeState()
    {
        $this->validate();
        Employee::create([
           'country_id' => $this->countryId,
           'name'         => $this->name
       ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully created');
    }
    public function updateState()
    {
        $validated = $this->validate([
            'countryId' => 'required',
            'name'        => 'required'
        ]);
        $state = Employee::find($this->stateId);
        $state->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully updated');
    }
    public function render()
    {
        $employees = Employee::paginate(5);
        if (strlen($this->search) > 2) {
            $employees = Employee::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.employee.employee-index', ['employees' => $employees])->layout('layouts.main');
    }
}
