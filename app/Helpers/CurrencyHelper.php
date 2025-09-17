<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format amount with default currency
     */
    public static function format($amount, $decimals = null)
    {
        $currency = config('currency.default');
        $decimals = $decimals ?? $currency['decimals'];

        return number_format($amount, $decimals) . ' ' . $currency['symbol'];
    }

    /**
     * Format amount for payment gateway
     */
    public static function formatPayment($amount, $decimals = null)
    {
        $currency = config('currency.payment');
        $decimals = $decimals ?? $currency['decimals'];

        return number_format($amount, $decimals) . ' ' . $currency['symbol'];
    }

    /**
     * Get default currency symbol
     */
    public static function getSymbol()
    {
        return config('currency.default.symbol');
    }

    /**
     * Get payment currency symbol
     */
    public static function getPaymentSymbol()
    {
        return config('currency.payment.symbol');
    }

    /**
     * Get default currency code
     */
    public static function getCode()
    {
        return config('currency.default.code');
    }

    /**
     * Get payment currency code
     */
    public static function getPaymentCode()
    {
        return config('currency.payment.code');
    }

    /**
     * Get default currency name
     */
    public static function getName()
    {
        return config('currency.default.name');
    }

    /**
     * Get payment currency name
     */
    public static function getPaymentName()
    {
        return config('currency.payment.name');
    }
}
