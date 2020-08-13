<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\{AttributeGroup, Attribute, UserActivity, Event};
use DB;
use Respect\Validation\Validator as v;
use Slim\Exception\NotFoundException;
use Carbon\Carbon as Carbon;

class ProductInfoController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////                     Variations Groups                        //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**************************************************************************************************************************************************
     ************************************************************(Variations get All )*****************************************************************
     **************************************************************************************************************************************************/
    public function variationsGroupsGet($request, $response, $args)
    {
        // Select all Variations groups with thier variations
        $VariationGroups = DB::query("SELECT variations.id,variation_groups.id as variation_group_id , JSON_UNQUOTE(JSON_EXTRACT(variations.name, '$." . language . "'))  as name,JSON_UNQUOTE(JSON_EXTRACT(variation_groups.name, '$." . language . "')) as variation_group_name FROM variation_groups LEFT JOIN variations ON variations.variation_group_id = variation_groups.id order by name");
        // Soort all variations in thier groups
        $VariationGroupsrTmp = [];
        foreach ($VariationGroups as $key => $value) {
            $VariationGroupsrTmp[$value['variation_group_name']]['variations'][$value['id']] = $value;
            $VariationGroupsrTmp[$value['variation_group_name']]['id'] = $value['variation_group_id'];
        }
        $VariationGroups = $VariationGroupsrTmp;

        //get all images in specfic product
        $images = DB::query("SELECT *, JSON_UNQUOTE(JSON_EXTRACT(title, '$." . language . "')) as title FROM product_meta WHERE product_id=%i ORDER BY sort_order ASC", $request->getParam('product_id'));
        $currentVariations = DB::query('SELECT * from product_variation where product_id=%i order by id', $request->getParam('product_id'));

        $rows = [];
        foreach ($currentVariations as $currentVariation) {
            $rowTmp = $currentVariation;
            foreach ($VariationGroups as $VariationGroup) {
                foreach ($VariationGroup['variations'] as $variation) {
                    if ($currentVariation['variation_id'] == $variation['id']) {
                        $rowTmp['variation_name'] = $variation['name'];
                        $rowTmp['variation_group_id'] = $variation['variation_group_id'];
                        $rowTmp['variation_group_name'] = $variation['variation_group_name'];
                    } elseif ($currentVariation['sub_variation_id'] == $variation['id']) {
                        $rowTmp['sub_variation_name'] = $variation['name'];
                        $rowTmp['sub_variation_group_id'] = $variation['variation_group_id'];
                        $rowTmp['sub_variation_group_name'] = $variation['variation_group_name'];
                    }
                }
            }
            $rows[] = $rowTmp;
        }
        $currentVariations = $rows;

        return $response->withJson(['variationGroups' => $VariationGroups, 'images' => $images, 'currentVariations' => $currentVariations]);
    }

    /**************************************************************************************************************************************************
     *********************************************************( Product Variations Update )************************************************************
     **************************************************************************************************************************************************/
    public function productVariationsPost($request, $response, $args)
    {
        UserActivity::Record('Update', $request->getParam('product_id'), 'Product Variations');

        //  DB::delete('product_variation', "product_id=%i", $request->getParam('product_id'));
        $checking = true;
        if ($request->getParam('productVariations')) {
            foreach ($request->getParam('productVariations') as $productVariation) {
                if ($productVariation['active']) {
                    $active = true;
                } else {
                    $active = false;
                }

                if (!$productVariation['subVariationId'] || $productVariation['subVariationId'] == '') {
                    $subVariationId = null;
                } else {
                    $subVariationId = $productVariation['subVariationId'];
                }
                if (($productVariation['variationId'] && $productVariation['image_id'] && $productVariation['price'])) {
                    if ($productVariation['new'] == 1) {
                        $check = DB::insert('product_variation', [
                            'product_id' => $request->getParam('product_id'),
                            'variation_id' => $productVariation['variationId'], 'sub_variation_id' => $subVariationId, 'image_id' => $productVariation['image_id'],
                            'price' => $productVariation['price'], 'ean' => $productVariation['ean'], 'active' => $active
                        ]);
                        if ($check) {
                            gereateEanForVariation(DB::insertId(), $request->getParam('product_id'));
                        }
                    } else {
                        $check = DB::update(
                            'product_variation',
                            [
                                'product_id' => $request->getParam('product_id'),
                                'variation_id' => $productVariation['variationId'], 'sub_variation_id' => $subVariationId,
                                'image_id' => $productVariation['image_id'],
                                'price' => $productVariation['price'], 'ean' => $productVariation['ean'], 'active' => $active
                            ],
                            'id=%i',
                            $productVariation['id']
                        );
                    }

                    if (!$check) {
                        $checking = false;
                        break;
                    }
                } else {
                    return $response->withJson(['status' => 'false', 'msg' => 'De variaties zijn niet bijgewerkt, missende data']);
                }
            }
            if ($checking) {
                return $response->withJson(['status' => 'true', 'msg' => 'De variaties zijn bijgewerkt']);
            }
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De variaties zijn niet bijgewerkt']);
    }

    /**************************************************************************************************************************************************
     ***************************************************************(Reviews New Index Get)************************************************************
     **************************************************************************************************************************************************/
    public function getNewReviews($request, $response, $args)
    {
        return $this->view->render($response, 'reviews/index.tpl', ['active_menu' => 'products',
        'page_title' => 'Niewe Productreviews']);
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Reviews Get Data)*************************************************************
     **************************************************************************************************************************************************/
    public function getNewReviewsData($request, $response, $args)
    {
        $reviews = DB::query("select product_review.id,product_review.product_id,product_review.created_at,product_review.contents
         ,product.contents->>'$.nl.title' as product_title
         from product_review
         left join product on product.id = product_review.product_id
         where locked=1
         order by created_at DESC");
        foreach ($reviews as $key => $review) {
            $reviews[$key]['contents'] = json_decode($review['contents'], true);
        }

        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($reviews),
            'recordsFiltered' => count($reviews),
            'data' => $reviews
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ****************************************************************(Reviews Get All Data)************************************************************
     **************************************************************************************************************************************************/
    public function getAllReviewsData($request, $response, $args)
    {
        $reviews = DB::query("select product_review.id,product_review.product_id,product_review.created_at,product_review.locked,product_review.contents
         ,product.contents->>'$.nl.title' as product_title
         from product_review
         left join product on product.id = product_review.product_id
         order by created_at DESC");
        foreach ($reviews as $key => $review) {
            $reviews[$key]['contents'] = json_decode($review['contents'], true);
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($reviews),
            'recordsFiltered' => count($reviews),
            'data' => $reviews
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     **********************************************************************(Update Reviews )***********************************************************
     **************************************************************************************************************************************************/
    public function reviewsUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty()
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Review data klopt niet !!']);
        }
        UserActivity::Record('Update Product Reviews locked to ' . $request->getParam('status', 1), $request->getParam('id'), 'Product Reviews');
        $check = DB::update('product_review', ['locked' => $request->getParam('status', 1), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')], 'id=%i', $request->getParam('id'));
        if ($check) {
            //run event
            $review = DB::queryFirstRow('select product_id from product_review where id=%i', $request->getParam('id'));
            $event = new Event();
            $event->productUpdated($review['product_id'], '', 'reviews');
            return $response->withJson(['status' => 'true', 'msg' => 'De review is bijgewerkt']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'De review is niet bijgewerkt']);
        }
    }
}
