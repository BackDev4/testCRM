<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormDataRequest;
use App\Models\FormData;
use Illuminate\Http\Request;

class FormDataController extends Controller
{
    public function create(FormDataRequest $request)
    {
        $formData = new FormData();
        $formData->hook = json_encode($request->validated());
        $formData->form = $request->form;
        $formData->save();

        $this->createTaskAndDeal($request->validated());

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
        $fio = $data['fio'];
        $price = $data['price'];

        $leadsService = $amo->leads();
        $deal = $leadsService->create();
        $deal->name = $name;
        $deal->sale = $price;

        $deal->attachTag($city);
        $deal->save();

        $contactsService = $amo->contacts();
        $contact = $contactsService->create();
        $contact->name = $fio;
        $contact->cf('Телефон')->setValue($phone, "Work");
        $contact->save();

        $deal->contacts_id = [$contact->id];
        $deal->save();

        return true;
    }
}
