<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendEmail;
use SendinBlue\Client\Model\SendSmtpEmail;
use App\Classes\Pdf;

class Mail
{
    public $SMTPApi = '';
    private $AccountApi = '';
    private $parameters;

    /**
     * __construct function
     */
    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', SENDINBLUE_KEY);
        $this->SMTPApi = new \SendinBlue\Client\Api\SMTPApi(new \GuzzleHttp\Client(), $config);
        $this->AccountApi = new \SendinBlue\Client\Api\AccountApi(new \GuzzleHttp\Client(), $config);
    }
    /**
     * prepareSentMailDataTransac  function, It is for sendinblue
     *
     * @param string $parameters
     * @return void
     */
    public function prepareMailDataTransac($parameters = '')
    {
       // roblaanstra15@gmail.com
        $parameters['sender'] = array(
            'email' => 'support@123bestdeal.nl',
            'name' => '123BestDeal Klantenservice'
        );
        $this->parameters = new SendSmtpEmail($parameters);
    }

    /**
     * prepareOrderMailNewData mail function for new status
     *
     * @param int $order
     * @param array $status
     * @param array $parameters
     * @param int $template
     * @return void
     */
    public function prepareOrderNewMailData($order = '', $status = '', $parameters = '', $template = 1)
    {
        $table = $this->getOrderRows($order);
        $emailContent = str_replace('%ORDERITEMS%', $table, $status['email_content']);
        $emailContent = str_replace('%ORDERID%', $order['id'], $emailContent);
        $emailContent = str_replace('%LASTNAME%', $order['order_details']['address']['payment']['lastname'], $emailContent);
        $emailContent = str_replace('%ADDRESS%', $order['order_details']['address']['shipping']['street'] . ' '
        . $order['order_details']['address']['shipping']['houseNumber'] . ' '
        . $order['order_details']['address']['shipping']['houseNumberSupplement'], $emailContent);
        $emailContent = str_replace('%NAME%', $order['order_details']['address']['shipping']['firstname'] . " " . $order['order_details']['address']['shipping']['lastname'], $emailContent);
        $emailContent = str_replace('%ZIPCODE%', $order['order_details']['address']['shipping']['zipcode'], $emailContent);
        $emailContent = str_replace('%CITY%', $order['order_details']['address']['shipping']['city'], $emailContent);
        $emailContent = str_replace('%COUNTRY%', $order['order_details']['address']['shipping']['countryCode'], $emailContent);
        $emailContent = str_replace('<p>', '<p style="margin:20px 0 !important;font-size:14px !important;">', $emailContent);
        //prepare mail parameters
        //tags
        $tmpParameters['tags'] = [];
        foreach ($parameters['tags'] as $tag) {
            $tmpParameters['tags'][] = $tag;
        }
        // subject
        $tmpParameters['subject'] = str_replace('%ORDERID%', $order['id'], $parameters['email_subject']);

        // add mail content
        $tmpParameters['params'] = ['EMAIL_CONTENTS' => $emailContent];

        // add template id
        $tmpParameters['templateId'] = (int) $template;

        // add to
        $tmpParameters['to'][] = ['name' => $order['order_details']['address']['payment']['firstname'] . ' ' .
        $order['order_details']['address']['payment']['lastname'], 'email' => $order['order_details']['customerEmail']];

        $this->prepareMailDataTransac($tmpParameters);
    }
    /**
     * prepareOrderNewIfomUsMailData mail function for new status, it fire when a new order imported and send an emaol to us
     *
     * @param int $order
     * @param array $status
     * @param array $parameters
     * @param int $template
     * @return void
     */
    public function prepareOrderNewInfomUsMailData($order = '', $status = '', $parameters = '', $template = 1)
    {
        $table = $this->getOrderRows($order);
        $emailContent = str_replace('%ORDERITEMS%', $table, $status['email_content']);
        $emailContent = str_replace('%ORDERID%', $order['id'], $emailContent);
        $emailContent = str_replace('%LASTNAME%', $order['order_details']['address']['payment']['lastname'], $emailContent);
        $emailContent = str_replace('%ADDRESS%', $order['order_details']['address']['shipping']['street'] . ' '
        . $order['order_details']['address']['shipping']['houseNumber'] . ' '
        . $order['order_details']['address']['shipping']['houseNumberSupplement'], $emailContent);
        $emailContent = str_replace('%NAME%', $order['order_details']['address']['shipping']['firstname'] . " " . $order['order_details']['address']['shipping']['lastname'], $emailContent);
        $emailContent = str_replace('%ZIPCODE%', $order['order_details']['address']['shipping']['zipcode'], $emailContent);
        $emailContent = str_replace('%CITY%', $order['order_details']['address']['shipping']['city'], $emailContent);
        $emailContent = str_replace('%COUNTRY%', $order['order_details']['address']['shipping']['countryCode'], $emailContent);
        $emailContent = str_replace('<p>', '<p style="margin:20px 0 !important;font-size:14px !important;">', $emailContent);
        //prepare mail parameters
        //tags
        $tmpParameters['tags'] = [];
        foreach ($parameters['tags'] as $tag) {
            $tmpParameters['tags'][] = $tag;
        }
        // subject
        $tmpParameters['subject'] = str_replace('%ORDERID%', $order['id'], $parameters['email_subject']);

        // add mail content
        $tmpParameters['params'] = ['EMAIL_CONTENTS' => $emailContent];

        // add template id
        $tmpParameters['templateId'] = (int) $template;

        // add to
        $tmpParameters['to'][] = ['name' => 'sam', 'email' => 'sam@123bestdeal.nl'];
        $tmpParameters['to'][] = ['name' => 'algemeen', 'email' => 'algemeen@123bestdeal.nl'];

        $this->prepareMailDataTransac($tmpParameters);
    }

    /**
     * prepareOrderSentMailData mail function for sent status
     *
     * @param int $order
     * @param array $status
     * @param array $parameters
     * @param int $template
     * @return void
     */
    public function prepareOrderSentMailData($order = '', $status = '', $parameters = '', $template = 1)
    {
        //generate the invoice data from Order class
        $orderNew = new Order();
        $pdf = $orderNew->pdfGeneratorOrder($order, $order['id']);
        if ($pdf) {
            $file = $pdf->generateOrderPdfString($order['id']);
        } else {
            return false;
        }
        $table = $this->getOrderRows($order);
        //get track and trace
        if ($order['tracktrace']) {
            $trackTrace = 'Uw bestelling is als Track&Trace pakket verzonden met Selektvracht, <a href="https://www.dhlparcel.nl/nl/volg-uw-zending?tc=' . $order['tracktrace'] . '&pc=' . $order['order_details']['address']['shipping']['zipcode'] . '" target="_blank"> klik hier voor de status van uw zending </a>
            <br>Let op: het kan enkele uren duren voordat uw zending zichtbaar wordt op de Track&Trace website';
        } else {
            $trackTrace = 'Uw bestelling is als brievenbus zending verzonden, u hoeft dus niet thuis te blijven voor de postbode!';
        }
        $emailContent = str_replace('%TRACKTRACE%', $trackTrace, $status['email_content']);
        $emailContent = str_replace('%ORDERITEMS%', $table, $emailContent);
        $emailContent = str_replace('%ORDERID%', $order['id'], $emailContent);
        $emailContent = str_replace('%LASTNAME%', $order['order_details']['address']['payment']['lastname'], $emailContent);
        $emailContent = str_replace('<p>', '<p style="margin:20px 0 !important;font-size:14px !important;">', $emailContent);

        //prepare mail parameters
        //tags
        $tmpParameters['tags'] = [];
        foreach ($parameters['tags'] as $tag) {
            $tmpParameters['tags'][] = $tag;
        }
        // subject
        $tmpParameters['subject'] = str_replace('%ORDERID%', $order['id'], $parameters['email_subject']);

        // add mail content
        $tmpParameters['params'] = ['EMAIL_CONTENTS' => $emailContent];

        // add template id
        $tmpParameters['templateId'] = (int) $template;

        // add to
        $tmpParameters['to'][] = ['name' => $order['order_details']['address']['payment']['firstname'] . ' ' .
        $order['order_details']['address']['payment']['lastname'], 'email' => $order['order_details']['customerEmail']];

        //add attachement
        $tmpParameters['attachment'][] = ['content' => $file, 'name' => 'factuur_' . $order['id'] . '.pdf'];

        $this->prepareMailDataTransac($tmpParameters);
    }

    /**
     * prepareOrderOtherStatusMailData mail function for other statuses of order
     *
     * @param int $order
     * @param array $status
     * @param array $parameters
     * @param int $template
     * @return void
     */
    public function prepareOrderOtherStatusMailData($order = '', $status = '', $parameters = '', $template = 1)
    {
        $table = $this->getOrderRows($order);
        $emailContent = str_replace('%ORDERITEMS%', $table, $status['email_content']);
        $emailContent = str_replace('%ORDERID%', $order['id'], $emailContent);
        $emailContent = str_replace('%LASTNAME%', $order['order_details']['address']['payment']['lastname'], $emailContent);
        $emailContent = str_replace('<p>', '<p style="margin:20px 0 !important;font-size:14px !important;">', $emailContent);

        //prepare mail parameters
        //tags
        $tmpParameters['tags'] = [];
        foreach ($parameters['tags'] as $tag) {
            $tmpParameters['tags'][] = $tag;
        }
        // subject
        $tmpParameters['subject'] = str_replace('%ORDERID%', $order['id'], $parameters['email_subject']);

        // add mail content
        $tmpParameters['params'] = ['EMAIL_CONTENTS' => $emailContent];

        // add template id
        $tmpParameters['templateId'] = (int) $template;

        // add to
        $tmpParameters['to'][] = ['name' => $order['order_details']['address']['payment']['firstname'] . ' ' .
        $order['order_details']['address']['payment']['lastname'], 'email' => $order['order_details']['customerEmail']];

        $this->prepareMailDataTransac($tmpParameters);
    }

    public function sendMailTemplate($templateId)
    {
        try {
            return $result = $this->SMTPApi->sendTransacEmail($this->parameters);
        } catch (\Exception $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            die('The Email is not sent');
        }
    }

    public function sendMailTransac()
    {
        try {
            return $result = $this->SMTPApi->sendTransacEmail($this->parameters);
        } catch (\Exception $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            die('The Email is not sent');
        }
    }

    public function getAccount()
    {
        try {
            return $result = $this->AccountApi->getAccount();
        } catch (\Exception $e) {
            echo 'Exception when calling AccountApi->getAccount: ', $e->getMessage(), PHP_EOL;
            die();
        }
    }

    public function getTemplates()
    {
        $templateStatus = true; // bool | Filter on the status of the template. Active = true, inactive = false
        $limit = 50; // int | Number of documents returned per page
        $offset = 0; // int | Index of the first document in the page
        try {
            $result = $this->SMTPApi->getSmtpTemplates(null, $limit, $offset);
            $templates = [];

            foreach ($result['templates'] as $template) {
                if ($template['isActive']) {
                    $templates[$template['id']] = $template;
                }
            }
            return $templates;
        } catch (Exception $e) {
            echo 'Exception when calling SMTPApi->getSmtpTemplates: ', $e->getMessage(), PHP_EOL;
            die();
        }
    }

    /**
     * getOrderRows function, generate the table of order items
     *
     * @param array $order
     * @return string
     */
    public function getOrderRows($order)
    {
        $tmp = '<table width=100%><thead><tr><th width=30px></th><th>Artikel</th><th align=right>Prijs /st</th></thead><tbody>';
        foreach ($order['order_items'] as $item) {
            $attributes = '';
            if (!isset($item['attributes'])) {
                $item['attributes'] = [];
            }
            foreach ($item['attributes'] as $attribute) {
                if ($attributes != '') {
                    $attributes .= ' | ';
                } else {
                    $attributes = ' - ';
                }
                $attributes .= $attribute['title'];
            }
            $tmp .= "<tr><td>" . $item['count'] . "</td><td>" . $item['product_name'] . "</td><td align=right>€ " . number_format($item['price'], 2, '.', ',') . "</td></tr>";
        }
        $total = number_format($order['gross_price'], 2, '.', ',');
        $tmp .= "<tr><td></td><b>Totaal</b><td></td><td align=right> € " . $total . "</td></tr>";
        $tmp .= "</tbody></table>";
        return $tmp;
    }
}
