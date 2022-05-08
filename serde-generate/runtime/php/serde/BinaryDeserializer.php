<?php


abstract class BinaryDeserializer implements Deserializer
{
    protected $input;
    private $containerDepthBudget;

    public function __construct($input, int $maxContainerDepth)
    {
        $input = ByteBuffer . wrap(input);
        $input->order(ByteOrder . LITTLE_ENDIAN);
        $containerDepthBudget = $maxContainerDepth;
    }

    public function increase_container_depth()
    {
        if ($this->containerDepthBudget == 0) {
            throw new DeserializationError("Exceeded maximum container depth");
        }
        $this->containerDepthBudget -= 1;
    }

    public function decrease_container_depth()
    {
        $this->containerDepthBudget += 1;
    }

    public function deserialize_str(): string
    {
        $len = $this->deserialize_len();
        if ($len < 0 || $len > PHP_INT_MAX) {
            throw new DeserializationError("Incorrect length value for Java string");
        }
        $content = array();
        $this->read($content);
//        $decoder = StandardCharsets.UTF_8.newDecoder();
//        try {
//            decoder.decode(ByteBuffer.wrap(content));
//        } catch (CharacterCodingException ex) {
//        throw new DeserializationError("Incorrect UTF8 string");
//        }
        return $content;
    }

    public function deserialize_bytes()
    {
        $len = $this->deserialize_len();
        if ($len < 0 || $len > PHP_INT_MAX) {
            throw new DeserializationError("Incorrect length value for php array");
        }
        $content = array();
        $this->read($content);
        return new Bytes($content);
    }

    public function deserialize_bool(): bool
    {
        $value = $this->getByte();
        if ($value == 0) {
            return false;
        }
        if ($value == 1) {
            return true;
        }
        throw new DeserializationError("Incorrect boolean value");
    }

    public function deserialize_unit(): int
    {

    }

    public function deserialize_char(): string
    {
        throw new DeserializationError("Not implemented: deserialize_char");
    }

    public function deserialize_u8(): int
    {
        return $this->getByte();
    }

    public function deserialize_u16(): int
    {
        return $this->getShort();
    }

    public function deserialize_u32(): int
    {
        return $this->getInt();
    }

    public function deserialize_u64(): int
    {
        return $this->getLong();
    }

    public function deserialize_u128(): int
    {
        $signed = $this->deserialize_i128();
        if ($signed >= 0) {
            return $signed;
        } else {
            return $signed->add(shiftLeft(128));
        }
    }

    public function deserialize_i8(): int
    {
        return $this->getByte();
    }

    public function deserialize_i16(): int
    {
        return $this->getShort();
    }

    public function deserialize_i32(): int
    {
        return $this->getInt();
    }

    public function deserialize_i64(): int
    {
        return $this->getLong();
    }

    public function deserialize_i128(): int
    {
        $content = array();
        $this->read($content);
        $reversed = array();
        for ($i = 0; $i < 16; $i++) {
            $reversed[$i] = $content[15 - $i];
        }
        return $reversed;
    }

    public function deserialize_option_tag(): bool
    {
        return $this->deserialize_bool();
    }

    public function get_buffer_offset(): int
    {
        return $this->input->position();
    }

    const INPUT_NOT_LARGE_ENOUGH = "Input is not large enough";

    protected function getByte(): int
    {
        try {
            return $this->input->get();
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

    protected function getShort(): int
    {
        try {
            return $this->input->getShort();
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

    protected function getInt(): int
    {
        try {
            return $this->input->getInt();
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

    protected function getLong(): int
    {
        try {
            return $this->input->getLong();
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

    protected function getFloat(): float
    {
        try {
            return $this->input->getFloat();
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

    protected function getDouble(): float
    {
        try {
            return $this->input->getDouble();
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

    protected function read($content)
    {
        try {
            $this->input->get($content);
        } catch (\Exception $exception) {
            throw new DeserializationError(self::INPUT_NOT_LARGE_ENOUGH);
        }
    }

}