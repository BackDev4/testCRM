<?php

namespace App\Http\Controllers;

use App\Models\FormData;
use Illuminate\Http\Request;
use Ufee\Amo\Models\Contact;
use Ufee\Amo\Models\Lead;
use Ufee\Amo\AmoAPI;

class FormDataController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'city' => 'required',
            'date' => 'required|date',
            'form' => 'required',
            'phone' => 'required|min:9',
        ]);

        $formData = new FormData();
        $formData->hook = json_encode($validatedData);
        $formData->form = $request->form;
        $formData->save();

        $this->createTaskAndDeal($validatedData);

        return response()->json($formData);
    }

    public function createTaskAndDeal($data)
    {
        $auth = new AmoAuth();
        $auth->connect();
        $amo = $auth->getAmoAPI();

        $name = $data['name'];
        $phone = $data['phone'];
        $city = $data['city'];

        $leadsService = $amo->leads();
        $deal = $leadsService->create();
        $deal->name = $name;
        $deal->sale = 100;

        $deal->attachTag($city);
        $deal->save();

        $contactsService = $amo->contacts();
        $contact = $contactsService->create();
        $contact->name = "ФИО";
        $contact->cf('Телефон')->setValue($phone,"Work");
        $contact->save();

        $deal->contacts_id = [$contact->id];
        $deal->save();

        return true;
    }
}
