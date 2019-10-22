<?php

namespace App;

use http\Env\Request;

class cart
{
   public $items            = null;
   public $total_quantity   = 0;
   public $total_price      = 0;
    public function __construct($oldcart)
    {

        if ($oldcart)
        {
            $this->items             = $oldcart->items;
            $this->total_quantity    = $oldcart->total_quantity;
            $this->total_price       = $oldcart->total_price;
        }
    }

   public function add($item,$id,$quantaty)
   {
       $storedItems                 = ['qty' => $quantaty,'price' => $item->product_price*$quantaty, 'item' => $item];
       if ($this->items)
       {
           if (array_key_exists($id,$this->items))
           {
               $storedItems         = $this->items[$id];
               $storedItems['qty']  += $quantaty;
               $storedItems['price'] = $storedItems['price'] * $storedItems['qty'];
           }
       }
       $this->items[$id]              = $storedItems;
       $this->total_quantity         += $quantaty;
       $this->total_price            += $quantaty * $item['product_price'];
   }
   public function deleteItem($id)
   {

       foreach ($this->items as $item)
       {
          if ($item['item']['id']==$id)
          {
              $index   = array_search($item,$this->items);
          // array_splice($this->items,$index,10);
          unset($this->items[$index]);
          }
       }
       return $this->items;

   }

}
