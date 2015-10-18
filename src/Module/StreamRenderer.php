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
     * @var resource
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
        if (! is_array($ro->body)) {
            if (is_resource($ro->body)) {
                return $this->renameStream($ro->body);
            }
            return $this->renderer->render($ro);
        }
        foreach ($ro->body as &$item) {
            if (is_resource($item) && get_resource_type($item) == 'stream') {
                $item = $this->renameStream($item);
            }
        }

        return $this->renderer->render($ro);
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
        rewind($this->stream);
        $regex = sprintf('/(%s)/', implode('|', $this->hash));
        preg_match_all($regex, $string, $match, PREG_SET_ORDER);
        $list = $this->collect($match);
        $bodies = preg_split(
            $regex,
            $string
        );
        foreach ($bodies as $body) {
            fwrite($this->stream, $body);
            /** @var $stream \SplFileObject */
            $index = array_shift($list);
            if (isset($this->streams[$index])) {
                $stream = $this->streams[$index];
                rewind($stream);
                stream_copy_to_stream($stream, $this->stream);
            }
        }

        return $this->stream;
    }


    /**
     * @param $item
     *
     * @return string
     */
    private function renameStream($item)
    {
        $id = '__RES__' . md5(uniqid());
        $this->streams[$id] = $item;
        $this->hash[] = $id;
        $item = $id;

        return $item;
    }

    /**
     * @param $match
     *
     * @return array
     */
    private function collect($match)
    {
        $list = [];
        foreach ($match as $item) {
            $list[] = $item[0];
        }

        return $list;
    }
}
