<?php

namespace App\Http\Livewire;
use App\Models\Order;
use Livewire\Component;

class CartIndex extends Component


{
    public $count = 0;
    
    public function increment()

    {

        $this->count++;

    }
    

    public function decrement()

    {

        $this->count--;

    }

        public function render()
        {
            return view('livewire.cart-index');
        }
 
}