<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use DB;
use Carbon\Carbon;
use App\Auth\Auth as Auth;
use Respect\Validation\Validator as v;
use App\Classes\Product;
use App\Classes\Note;
use App\Classes\Order;
use App\Classes\UserActivity;

class NoteController extends Controller
{
    /**************************************************************************************************************************************************
     **************************************************************( NoteAddNew )**********************************************************************
     **************************************************************************************************************************************************/
    public function noteAddNew($request, $response, $args)
    {
        $note = new Note();
        $note->note = $request->getParam('msg');
        $note->user_id = $request->getParam('user_id');
        $note->user_id_to = $request->getParam('user_id_to');
        $note->order_id = $request->getParam('order_id');
        $note->product_id = $request->getParam('product_id');
        $note->status = 0;
        $id = $note->Create();
        if ($id) {
            //$note = Note::find($id);
            $username = DB::queryFirstRow('SELECT id,name,lastname,email,super FROM users WHERE id=%i', $note->user_id);
            $username_to = DB::queryFirstRow('SELECT id,name,lastname,email,super FROM users WHERE id=%i', $note->user_id_to);
            $temp = ['return' => 'Success', 'msg' =>  $note->note, 'username' => $username['name'], 'username_to' => $username_to['name']];
            return $response->withJson(['response' => $temp], 200);
        } else {
            return $response->withJson(['return' => 'Error']);
        }
    }

    /**************************************************************************************************************************************************
     **************************************************************( NoteAddNew )**********************************************************************
     **************************************************************************************************************************************************/
    public function noteAll($request, $response, $args)
    {
        $users = Auth::All();
        $model = Product::getAllProductsIds();

        $notesFrom = Note::From(Auth::user_id());
        $notesTo = Note::To(Auth::user_id());

        return $this->view->render($response, 'notes/all.tpl', ['active_menu' => 'notes',
        'page_title' => 'Alle Berichten','notesFrom' => $notesFrom, 'notesTo' => $notesTo, 'users' => $users]);
    }

    /**************************************************************************************************************************************************
     ******************************************************( Datatabel Get All Notes From someone to me )**********************************************
     **************************************************************************************************************************************************/
    public function noteAllFromData($request, $response, $args)
    {
        $notesFrom = Note::To(Auth::user_id());

        $notes = [];
        foreach ($notesFrom as $key => $note) {
            $notes[$key]['id'] = $note['id'];
            $notes[$key]['from'] = $note['name'];
            $notes[$key]['note'] = $note['note'];
            $notes[$key]['status'] = $note['status'];
            $notes[$key]['created_at'] = $note['created_at'];
            $notes[$key]['updated_at'] = $note['updated_at'];
            $notes[$key]['order_id'] = $note['order_id'];
            $notes[$key]['product_id'] = $note['product_id'];
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($notes),
            'recordsFiltered' => count($notes),
            'data' => $notes
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     *****************************************************( Datatabel Get All Notes To someone From me )***********************************************
     **************************************************************************************************************************************************/
    public function noteAllToData($request, $response, $args)
    {
        $notesFrom = Note::From(Auth::user_id());

        $notes = [];
        foreach ($notesFrom as $key => $note) {
            $notes[$key]['id'] = $note['id'];
            $notes[$key]['to'] = $note['name'];
            $notes[$key]['note'] = $note['note'];
            $notes[$key]['status'] = $note['status'];
            $notes[$key]['created_at'] = $note['created_at'];
            $notes[$key]['updated_at'] = $note['updated_at'];
            $notes[$key]['order_id'] = $note['order_id'];
            $notes[$key]['product_id'] = $note['product_id'];
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($notes),
            'recordsFiltered' => count($notes),
            'data' => $notes
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     *******************************************************************( Update Note Status )*********************************************************
     **************************************************************************************************************************************************/
    public function noteUpdateStatus($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'status' => v::intVal(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['return' => 'Error', 'msg' => 'There is aan error (validation error)']);
        }

        $check = DB::update('notes', [
            'status' => $request->getParam('status'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ], 'id=%i', $request->getParam('id'));

        if (DB::affectedRows() > 0) {
            UserActivity::Record('Update Status To ' . $request->getParam('status'), $request->getParam('id'), 'Notes');
            return $response->withJson(['return' => 'Success', 'msg' => 'De status is bijgewerkt'], 201);
        } else {
            return $response->withJson(['return' => 'Error', 'msg' => 'There is aan error']);
        }
    }

    /**************************************************************************************************************************************************
     ************************************************************( Get Product ids or Order ids )******************************************************
     **************************************************************************************************************************************************/
    public function getModels($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'selected_model' => v::notEmpty(),
            'search' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['return' => 'Error', 'msg' => 'There is aan error (validation error)']);
        }

        $model = $request->getParam('selected_model');
        $search = $request->getParam('search');
        if ($model == 'product') {
            $models = Product::getAllProductsIds($search);
        } elseif ($model == 'order') {
            $models = Order::getAllOrdersIds($search);
        }

        return $response->withJson(['return' => 'Success', 'model' => $models]);
    }
}
