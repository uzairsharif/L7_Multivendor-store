<?php
  
namespace App\Http\Livewire;
  
use Livewire\Component;
use App\Product;
  
class Products extends Component
{
    public $deleteId = '';
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function render()
    {
        return view('livewire.products', [
            'products' => Product::take(10)->get(),
        ])
        ->extends('layouts.app');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function deleteId($id)
    {
        $this->deleteId = $id;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function delete()
    {
        Product::find($this->deleteId)->delete();
    }
}