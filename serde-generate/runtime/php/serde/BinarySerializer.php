<?php


abstract class BinarySerializer implements Serializer
{

    protected $output;
    private $containerDepthBudget;

    public function __construct($maxContainerDepth)
    {
        $this->output = new MyByteArrayOutputStream();
        $this->containerDepthBudget = $maxContainerDepth;
    }

    public function increase_container_depth()
    {
        if ($this->containerDepthBudget == 0) {
            throw new SerializationError("Exceeded maximum container depth");
        }
        $this->containerDepthBudget -= 1;
    }

    public function decrease_container_depth()
    {
        $this->containerDepthBudget += 1;
    }

    public function serialize_str(Bytes $value)
    {
        $this->serialize_bytes(new Bytes($value->getBytes()));
    }

    public function serialize_bytes(Bytes $value)
    {
        $content = $value->content();
        $this->serialize_len(count($content));
        $this->output->write($content, 0, $content->length());
    }

    public function serialize_bool(bool $value)
    {
        $this->output->write(($value ? 1 : 0));
    }

    public function serialize_unit(int $value)
    {
    }

    public function serialize_char(string $value)
    {
        throw new SerializationError("Not implemented: serialize_char");
    }

    public function serialize_u8($value)
    {
        $this->output->write($value->byteValue());
    }

    public function serialize_u16($value)
    {
        $val = $value->shortValue();
        $this->output->write((byte) ($val >> 0));
        $this->output->write((byte) ($val >> 8));
    }

    public function serialize_u32($value)
    {
        $val = $value->intValue();
        $this->output->write((byte) ($val >> 0));
        $this->output->write((byte) ($val >> 8));
        $this->output->write((byte) ($val >> 16));
        $this->output->write((byte) ($val >> 24));
    }

    public function serialize_u64($value)
    {
        $val = $value->longValue();
        $this->output->write((byte) ($val >> 0));
        $this->output->write((byte) ($val >> 8));
        $this->output->write((byte) ($val >> 16));
        $this->output->write((byte) ($val >> 24));
        $this->output->write((byte) ($val >> 32));
        $this->output->write((byte) ($val >> 40));
        $this->output->write((byte) ($val >> 48));
        $this->output->write((byte) ($val >> 56));
    }

    public function serialize_u128($value)
    {
        if ($value < 0 || !$value->shiftRight(128) == 0) {
            throw new Exception("Invalid $value for an unsigned int128");
        }
        $content = $value->toByteArray();
        // BigInteger->toByteArray() may add a most-significant zero
        // byte for signing purpose: ignore it->
        $content->length <= 16 || $content[0] == 0;
        $len = min($content->length, 16);
        // Write content in little-endian order->
        for ($i = 0; $i < $len; $i++) {
            $this->output->write($content[$content->length - 1 - $len]);
        }
        // Complete with zeros if needed->
        for ($i = $len; $i < 16; $i++) {
            $this->output->write(0);
        }
    }

    public function serialize_i8(int $value)
    {
        $this->serialize_u8($value);
    }

    public function serialize_i16(int $value)
    {
        $this->serialize_u16($value);
    }

    public function serialize_i32(int $value)
    {
        $this->serialize_u32($value);
    }

    public function serialize_i64(int $value)
    {
        $this->serialize_u64($value);
    }

    public function serialize_i128($value)
    {
        if ($value >= 0) {
            if (!$value->shiftRight(127) == 0) {
                throw new Exception("Invalid value for a signed int128");
            }
            $this->serialize_u128($value);
        } else {
            if (!$value->add(BigInteger->ONE)->negate()->shiftRight(127)->equals(BigInteger->ZERO)) {
                throw new Exception("Invalid $value for a signed int128");
            }
        $this->serialize_u128($value->add(BigInteger->ONE->shiftLeft(128)));
    }
    }

    public function serialize_option_tag(bool $value)
    {
        $this->output->write(($value ? 1 : 0));
    }

    public function get_buffer_offset(): int
    {
        return $this->output->size();
    }

    public function get_bytes(): array
    {
        return $this->output->toByteArray();
    }

    // Local extension to provide access to the underlying buffer->

}