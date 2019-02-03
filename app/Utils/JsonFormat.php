<?php 
namespace App\Utils;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use MarcinOrlowski\ResponseBuilder\BaseApiCodes;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
class JsonFormat extends ResponseBuilder{

    const DEFAULT_API_CODE_OK = BaseResponse::HTTP_OK;

    public static function success($data = null, $api_code = null, array $lang_args = null, $http_code = null, $encoding_options = null)
    {
        if ($api_code === null) {
            $api_code = self::DEFAULT_API_CODE_OK;
        }

        return static::buildSuccessResponse($data, $api_code, $lang_args, $http_code, $encoding_options);
    }

    protected static function buildResponse($success, $api_code, $message, $data = null, array $trace_data = null)
    {

        // ensure data is serialized as object, not plain array, regardless what we are provided as argument
        if ($data !== null) {
            // we can do some auto-conversion on known class types, so check for that first
            /** @var array $classes */
            $classes = static::getClassesMapping();
            if (($classes !== null) && (count($classes) > 0)) {
                if (is_array($data)) {
                    static::convert($classes, $data);
                } elseif (is_object($data)) {
                    $obj_class_name = get_class($data);
                    if (array_key_exists($obj_class_name, $classes)) {
                        $conversion_method = $classes[$obj_class_name][static::KEY_METHOD];
                        $data = [$classes[$obj_class_name][static::KEY_KEY] => $data->$conversion_method()];
                    }
                }
            }
        }

        $response = [
            BaseApiCodes::getResponseKey(static::KEY_SUCCESS) => $success,
            BaseApiCodes::getResponseKey(static::KEY_CODE)    => $api_code,
            BaseApiCodes::getResponseKey(static::KEY_MESSAGE) => $message,
            BaseApiCodes::getResponseKey(static::KEY_DATA)    => $data,
        ];

        if ($trace_data !== null) {
            $debug_key = Config::get(static::CONF_KEY_DEBUG_DEBUG_KEY, ResponseBuilder::KEY_DEBUG);
            $response[$debug_key] = $trace_data;
        }

        if ($data !== null) {
            // ensure we get object in final JSON structure in data node
            $data = (object)$data;
        }

        return $response;
    }


}
