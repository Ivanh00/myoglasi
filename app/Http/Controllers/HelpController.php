<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function createListing()
    {
        return view('help.create-listing');
    }

    public function createAuction()
    {
        return view('help.create-auction');
    }

    public function createService()
    {
        return view('help.create-service');
    }

    public function createGiveaway()
    {
        return view('help.create-giveaway');
    }

    public function creditSystem()
    {
        return view('help.credit-system');
    }

    public function earnCredits()
    {
        return view('help.earn-credits');
    }

    public function transferCredits()
    {
        return view('help.transfer-credits');
    }

    public function pricing()
    {
        return view('help.pricing');
    }

    public function plans()
    {
        return view('help.plans');
    }

    public function promotions()
    {
        return view('help.promotions');
    }

    public function paymentMethods()
    {
        return view('help.payment-methods');
    }

    public function verification()
    {
        return view('help.verification');
    }

    public function faq()
    {
        return view('help.faq');
    }

    public function safety()
    {
        return view('help.safety');
    }

    public function terms()
    {
        return view('help.terms');
    }

    public function privacy()
    {
        return view('help.privacy');
    }

    public function contact()
    {
        return view('help.contact');
    }
}