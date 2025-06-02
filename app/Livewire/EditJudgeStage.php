<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Judges_Stages;

class EditJudgeStage extends Component
{
    public $stageId = null; // Null by default for creating a new record
    public $working_place = '';
    public $start_date = '';
    public $end_date = '';

    protected $rules = [
        'working_place' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $stage = Judges_Stages::findOrFail($id);
            $this->stageId = $stage->id;
            $this->working_place = $stage->working_place;
            $this->start_date = $stage->start_date;
            $this->end_date = $stage->end_date;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->stageId) {
            // Update existing record
            $stage = Judges_Stages::findOrFail($this->stageId);
            $stage->update([
                'working_place' => $this->working_place,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);
        } else {
            // Create a new record
            Judges_Stages::create([
                'working_place' => $this->working_place,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);
        }

        $this->emit('closeModal'); // Close the modal
        $this->emit('refreshTable'); // Refresh the table
    }

    public function render()
    {
        return view('livewire.edit-judge-stage');
    }
}
