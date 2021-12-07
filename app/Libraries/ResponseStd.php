<?php

namespace App\Libraries;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseStd
{
    /**
     * @param $items
     * @param string $messages
     * @param string $method
     * @return array
     */
    public static function ok($items, $messages = 'Success')
    {
        $return = [];
        $return['meta']['code'] = JsonResponse::HTTP_OK;
        $return['meta']['message'] = $messages;

        if ($items instanceof \Illuminate\Database\Eloquent\Model) {
            $return['pageinfo'] = self::emptyPageInfo();
        } else if (is_array($items)) {
            $return['page_info'] = self::arrayPageInfo($items);
        } else {
            $return['page_info'] = self::emptyPageInfo();
        }
        $return['errors'] = array();
        $return['data']['item'] = (object)[];
        if ($items instanceof \Illuminate\Database\Eloquent\Model) {
            $return['data']['items'] = array(new \Illuminate\Database\Eloquent\Collection($items));
        } else if (is_array($items)) {
            $return['data']['items'] = $items;
        } else {
            $return['data']['items'] = array($items);
        }

        return response()->json($return, 200);
    }

    /**
     * @param array $items
     * @param string $messages
     * @param string $method
     * @return array
     */
    public static function okNoOutput($messages = 'Success')
    {
        $return = [];
        $return['meta']['code'] = JsonResponse::HTTP_OK;
        $return['meta']['message'] = $messages;
        $return['page_info'] = self::emptyPageInfo();
        $return['errors'] = array();
        $return['data']['item'] = (object)[];
        $return['data']['items'] = array();

        return response()->json($return, 200);
    }

    /**
     * @param array $items
     * @param string $messages
     * @param string $method
     * @return array
     */
    public static function okSingle($item, $messages = 'Success')
    {
        $return = [];
        $return['meta']['code'] = JsonResponse::HTTP_OK;
        $return['meta']['message'] = $messages;
        $return['page_info'] = self::emptyPageInfo();
        $return['errors'] = array();
        $return['data']['item'] = $item;
        $return['data']['items'] = array();

        return response()->json($return, 200);
    }

    /**
     *
     * if "last_page > 1" on android must paginated.
     *
     * @param Paginator $paged
     * @param int $countAll
     * @param string $message
     * @param int $code
     * @return array
     */
    public static function paginated(Paginator $paged, int $countAll, $message = 'Success', int $code = 200)
    {
        $return = [];
        $return['meta']['code'] = $code;
        $return['meta']['message'] = $message;
        $return['page_info'] = (object)[
            "total" => $paged->total(),
            "per_page" => $paged->perPage(),
            "current_page" => $paged->currentPage(),
            "last_page" => $paged->lastPage(),
            "next_page_url" => $paged->nextPageUrl(),
            "prev_page_url" => $paged->previousPageUrl(),
            "from" => $paged->firstItem(),
            "count" => $countAll,
            "to" => $paged->lastItem(),
            "pagination" => true,
        ];
        $return['errors'] = array();
        $return['data']['item'] = (object)[];
        $return['data']['items'] = $paged->items();

        return response()->json($return, $code);
    }

    public static function paginatedItems($items, Paginator $paged, int $countAll, int $code = 200)
    {
        $return = [];
        $return['meta']['code'] = $code;
        $return['meta']['message'] = 'Success';
        $return['page_info'] = (object)[
            "total" => $paged->total(),
            "per_page" => $paged->perPage(),
            "current_page" => $paged->currentPage(),
            "last_page" => $paged->lastPage(),
            "next_page_url" => $paged->nextPageUrl(),
            "prev_page_url" => $paged->previousPageUrl(),
            "from" => $paged->firstItem(),
            "count" => $countAll,
            "to" => $paged->lastItem(),
            "pagination" => true,
        ];
        $return['errors'] = array();
        $return['data']['item'] = (object)[];
        if (is_array($items)) {
            $return['data']['items'] = $items;
        } else if (is_object($items)) {
            $return['data']['items'] = (object)($items);
        } else {
            $return['data']['items'] = $items;
        }

        return response()->json($return, $code);
    }

    public static function pagedFrom($items, Paginator $paged, int $countAll, int $code = 200)
    {
        $return = [];
        $return['meta']['code'] = $code;
        $return['meta']['message'] = 'Success';
        $return['page_info'] = (object)[
            'total' => $paged->total(),
            'per_page' => $paged->perPage(),
            'current_page' => $paged->currentPage(),
            'last_page' => $paged->lastPage(),
            'next_page_url' => $paged->nextPageUrl(),
            'prev_page_url' => $paged->previousPageUrl(),
            'from' => $paged->firstItem(),
            'count' => $countAll,
            'to' => $paged->lastItem(),
            'pagination' => true,
        ];
        $return['errors'] = [];
        $return['data']['item'] = (object)[];
        if (is_array($items)) {
            $return['data']['items'] = $items;
        } elseif (is_object($items)) {
            $return['data']['items'] = (object)($items);
        } else {
            $return['data']['items'] = $items;
        }

        return response()->json($return, $code);
    }

    /**
     * Create empty response.
     *
     * @return object
     */
    public static function emptyPageInfo()
    {
        $pageInfo = (object)[
            "total" => -1,
            "per_page" => -1,
            "current_page" => -1,
            "last_page" => -1,
            "next_page_url" => null,
            "prev_page_url" => null,
            "from" => -1,
            "to" => -1,
            "pagination" => false
        ];

        return $pageInfo;
    }

    /**
     * Jika "total <= per_page" di android tidak perlu paging.
     *
     * @param array $item
     * @return object
     */
    private static function arrayPageInfo(array $item)
    {
        $total = count($item);
        $pageInfo = (object)[
            "total" => $total,
            "per_page" => $total,
            "current_page" => 1,
            "last_page" => 1,
            "next_page_url" => null,
            "prev_page_url" => null,
            "from" => 1,
            "to" => $total,
            "pagination" => false
        ];

        return $pageInfo;
    }

    /**
     * @param $errors
     * @param int $code
     * @param string $messages
     * @return array
     */
    public static function fail($errors, $code = 400, $messages = 'Error')
    {
        $return = [];
        $return['meta']['code'] = $code;
        $return['meta']['message'] = $messages;
        $return['page_info'] = self::emptyPageInfo();
        if (is_array($errors)) {
            $return['meta']['errors'] = $errors;
        } else {
            $errors = [
                ["errors" => $errors]
            ];
            $return['meta']['errors'] = array($errors);
        }
        $return['data']['items'] = array();
        $return['data']['item'] = (object)[];

        return response()->json($return, $code);
    }

    /**
     * @param Validator $validator
     * @param string $messages
     * @return \Illuminate\Http\Response
     */
    public static function validation(
        Validator $validator,
        $messages = 'Validation Error')
    {
        $return = [];
        $return['meta']['code'] = 422;
        $return['meta']['message'] = $messages;
        $return['page_info'] = self::emptyPageInfo();
        $return['errors'] = array($validator->errors());
        $return['data']['items'] = array();
        $return['data']['item'] = (object)[];

        return response()->json($return, 422);
    }
}
