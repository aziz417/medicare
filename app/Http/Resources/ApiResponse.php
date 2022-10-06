<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse extends JsonResource
{
    /**
     * Response code
     */
    protected $code = 200;

    /**
     * Response message
     */
    protected $message = null;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $statusCode = 200, $message = null)
    {
        parent::__construct($resource);
        $this->code = $statusCode;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'status' => true,
            'code' => $this->code ?? 200,
            'data' => null
        ];
        $data = $this->getResourceData($request);
        if( $this->code >= 200 && $this->code < 300 ){
            if( is_string($data) ){
                $response['message'] = $data;
            }else{
                $response['data'] = $data;
            }
        }else{
            if( is_string($data) ){
                $response['message'] = $data;
            }else{
                $response['data'] = $data;
            }
        }
        $response['message'] = $this->message ?? $response['message'] ?? "Successful";
        return $response;
    }

    /**
     * Customize the response for a request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }

    /**
     * Get the resources data
     * @param $request
     * @return mixed
     */
    public function getResourceData($request)
    {
        if( is_array($this->resource) ){
            return $this->resource;
        }elseif( is_string($this->resource) ){
            return $this->resource;
        }elseif( is_json($this->resource) ){
            return json_decode($this->resource, true);
        }elseif( is_object($this->resource) && method_exists($this->resource, 'toArray') ){
            return $this->resource->toArray();
        }else{
            return parent::toArray($request);
        }
    }
}
