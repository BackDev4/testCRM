<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormDataRequest;
use App\Models\FormData;

class FormDataController extends Controller
{
    /**
     * @group FormData
     *
     * Создает новую запись формы данных.
     *
     * @bodyParam fio string required ФИО. Example: Иванов Иван Иванович
     * @bodyParam name string required Наименование. Example: Новая запись
     * @bodyParam price integer required Цена. Example: 100
     * @bodyParam city string required Город. Example: Москва
     * @bodyParam date string required Дата. Example: 2024-04-30
     * @bodyParam form string required Форма. Example: Форма №1
     * @bodyParam phone string required Номер телефона. Example: 1234567890
     *
     * @response {
     *   "id": 1,
     *   "hook": "{\"fio\":\"Иванов Иван Иванович\",\"name\":\"Новая запись\",\"price\":100,\"city\":\"Москва\",\"date\":\"2024-04-30\",\"form\":\"Форма №1\",\"phone\":\"1234567890\"}",
     *   "form": "Форма №1"
     * }
     *
     * @response 422 {
     *   "message": "Неверный формат данных"
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */

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
