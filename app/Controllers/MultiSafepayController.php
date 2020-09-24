<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\{
  UserActivity,
  Order
};
use App\Classes\MultiSafepay;
use DB;
use Carbon\Carbon as Carbon;
use App\Auth\Auth;
use Respect\Validation\Validator as v;

class MultiSafepayController extends Controller
{
  private $msp;
  public function __construct($container)
  {
    $this->msp = new MultiSafepay;
    parent::__construct($container);
  }
  public function getStatus($request, $response, $args)
  {
    $error = '';
    $validation = $this->validator->validateGet($args, [
      'transaction-id' => v::notEmpty(),
    ]);

    ///check if failed return back with error message and the fields
    if ($validation->failed()) {
      return $response->withJson(['status' => 'false', 'msg' => 'Missende transactie ID']);
    }
    try {
      $transactionid = $args['transaction-id'];
      //get the order
      $order = $this->msp->MSP->orders->get($endpoint = 'orders', $transactionid, $body = array(), $query_string = false);
    } catch (\Exception $e) {
      $error = "Error " . htmlspecialchars($e->getMessage());
    }
    if (isset($order)) {
      return $this->view->render($response, 'orders/single/modals/msp_status.tpl', ['paymentStatus' => $order]);
    } else {
      return $response->withJson(['status' => 'false', 'msg' => $error]);
    }
  }
}
