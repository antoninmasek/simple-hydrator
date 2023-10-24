<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

class ImageData
{
    #[\AntoninMasek\SimpleHydrator\Attributes\Key('ExifImageWidth')]
    public int $width;

    #[\AntoninMasek\SimpleHydrator\Attributes\Key('ExifImageHeight')]
    public int $height;
}
