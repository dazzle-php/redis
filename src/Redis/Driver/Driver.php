<?php
namespace Dazzle\Redis\Driver;

use Clue\Redis\Protocol\Model\ModelInterface;
use Clue\Redis\Protocol\Model\Request;
use Clue\Redis\Protocol\Parser\RequestParser;
use Clue\Redis\Protocol\Parser\ResponseParser;
use Clue\Redis\Protocol\Serializer\RecursiveSerializer;

class Driver implements DriverInterface
{
    /**
     * @var RequestParser
     */
    protected $requestParser;

    /**
     * @var ResponseParser
     */
    protected $responseParser;

    /**
     * @var RecursiveSerializer
     */
    protected $serializer;

    /**
     *
     */
    public function __construct()
    {
        $this->requestParser = new RequestParser();
        $this->responseParser = new ResponseParser();
        $this->serializer = new RecursiveSerializer();
    }

    /**
     * @inheritDoc
     */
    public function commands(Request $request)
    {
        return $this->serializer->getRequestMessage(
            $request->getCommand(),
            $request->getArgs()
        );
    }

    /**
     * @return RecursiveSerializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @return RequestParser
     */
    public function getRequestParser()
    {
        return $this->requestParser;
    }

    /**
     * @return ResponseParser
     */
    public function getResponseParser()
    {
        return $this->responseParser;
    }

    /**
     * @param $data
     * @return ModelInterface[]
     */
    public function parseResponse($data)
    {
        return $this->responseParser->pushIncoming($data);
    }

    /**
     * @param $data
     * @return string
     */
    public function buildResponse($data)
    {
        return $this->serializer->getReplyMessage($data);
    }

}