<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Module;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Ray\Di\Di\Named;

class StreamRenderer implements RenderInterface
{
    /**
     * @var RenderInterface
     */
    private $renderer;

    /**
     * @var resource
     */
    private $stream;

    /**
     * Pushed stream
     *
     * @var resource[]
     */
    private $streams;

    /**
     * @var array
     */
    private $hash = [];

    /**
     * @param RenderInterface $renderer
     *
     * @Named("renderer=original,stream=BEAR\Middleware\Annotation\Stream")
     */
    public function __construct(RenderInterface $renderer, $stream)
    {
        $this->renderer = $renderer;
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ResourceObject $ro)
    {
        if (is_array($ro->body)) {
            $this->pushArrayBody($ro);

            return $this->renderer->render($ro);
        }

        return $this->pushScalarBody($ro);
    }

    /**
     * Covert string + stream holder to stream
     *
     * @param string $string
     *
     * @return resource
     */
    public function toStream($string)
    {
        if (! $this->hash) {
            fwrite($this->stream, $string);

            return $this->stream;
        }

        return $this->mergeStream($string, $this->stream);
    }

    /**
     * @param resource $item
     *
     * @return string
     */
    private function pushStream($item)
    {
        $id = uniqid('STREAM_' . rand(), true) . '_';
        $this->streams[$id] = $item; // push
        $this->hash[] = $id;
        $item = $id;

        return $item;
    }

    /**
     * @param string   $string
     * @param resource $stream
     *
     * @return resource
     */
    private function mergeStream($string, $stream)
    {
        rewind($stream);
        $regex = sprintf('/(%s)/', implode('|', $this->hash));
        preg_match_all($regex, $string, $match, PREG_SET_ORDER);
        $list = $this->collect($match);
        $bodies = preg_split($regex, $string);
        foreach ($bodies as $body) {
            fwrite($stream, $body);
            $index = array_shift($list);
            if (isset($this->streams[$index])) {
                $popStream = $this->streams[$index];
                rewind($popStream);
                stream_copy_to_stream($popStream, $stream);
            }
        }

        return $stream;
    }

    /**
     * @param array $match
     *
     * @return array
     */
    private function collect(array $match)
    {
        $list = [];
        foreach ($match as $item) {
            $list[] = $item[0];
        }

        return $list;
    }

    /**
     * @param ResourceObject $ro
     *
     * @return string
     */
    private function pushScalarBody(ResourceObject $ro)
    {
        if (is_resource($ro->body) && get_resource_type($ro->body) == 'stream') {
            return $this->pushStream($ro->body);
        }

        return $this->renderer->render($ro);
    }

    /**
     * @param ResourceObject $ro
     */
    private function pushArrayBody(ResourceObject $ro)
    {
        foreach ($ro->body as &$item) {
            if (is_resource($item) && get_resource_type($item) == 'stream') {
                $item = $this->pushStream($item);
            }
        }
    }
}
