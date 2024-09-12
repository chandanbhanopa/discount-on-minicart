<?php

namespace Chandan\DiscountOnMiniCart\Plugin;

use Magento\Checkout\CustomerData\Cart;
use Magento\Checkout\Model\Cart as MyCart;


class AddDiscountPlugin {

	protected $cart;

	public function __construct(MyCart $cart){
		$this->cart = $cart;
	}


    public function afterGetSectionData(Cart $subject, $result)
    {
		$data = $result;
		$quote = $this->cart->getQuote();
		$data['discount_amount'] = $quote->getSubtotal() - $quote->getSubtotalWithDiscount();  
		return $data;
        
    }
}