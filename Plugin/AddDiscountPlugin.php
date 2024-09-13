<?php

namespace Chandan\DiscountOnMiniCart\Plugin;

use Magento\Checkout\CustomerData\Cart;
use Magento\Checkout\Model\Cart as MyCart;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Tax\Model\Calculation as TaxCalculation;


class AddDiscountPlugin {

	protected $cart;

	protected $priceHelper;

	protected $taxCalculation;

	public function __construct(MyCart $cart, Data $priceHelper, TaxCalculation $taxCalculation){
		$this->cart = $cart;
		$this->priceHelper = $priceHelper;
		$this->taxCalculation = $taxCalculation;

	}


    public function afterGetSectionData(Cart $subject, $result)
    {
		$data = $result;
		$quote = $this->cart->getQuote();


		/******/
		$address = $quote->getShippingAddress();
		$taxRateRequest = $this->taxCalculation->getRateRequest(
			$address,
			null,
			null,
			$quote->getStore() // Store object
		);

		$productTaxClassId = $quote->getItems()[0]->getProduct()->getTaxClassId();
		$taxRate = $this->taxCalculation->getRate($taxRateRequest->setProductClassId($productTaxClassId));

		$totalTax = 0;
		foreach ($quote->getAllItems() as $item) {
			$rowTotal = $item->getRowTotal(); 
			$itemTax = $rowTotal * ($taxRate / 100); 
			$totalTax += $itemTax;
		}

		if ($quote->getShippingAddress()->getShippingAmount()) {
			$shippingAmount = $quote->getShippingAddress()->getShippingAmount();
			$shippingTax = $shippingAmount * ($taxRate / 100); // Assuming same tax rate for shipping
			$totalTax += $shippingTax;
		}

		$grandTotalWithTax = $quote->getGrandTotal()  ;
		$grandTotalWithoutTax = $quote->getGrandTotal() - $totalTax;; 
		/******/


		
		$discountAmount = $quote->getSubtotal() - $quote->getSubtotalWithDiscount();
		$data['discount_amount'] = $this->priceHelper->currency($discountAmount, true, false); 
		$data['grand_total_with_tax'] = $this->priceHelper->currency($grandTotalWithTax);
		$data['grand_total_without_tax'] = $this->priceHelper->currency($grandTotalWithoutTax);
		$data['my_tax'] = $this->priceHelper->currency($totalTax);

		
		return $data;
        
    }
}