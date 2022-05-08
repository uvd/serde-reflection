<?php


class BincodeDeserializer extends BinaryDeserializer
{

    public function deserialize_f32(): float
    {
        return $this->getFloat();
    }

    public function deserialize_f64(): float
    {
        return $this->getDouble();
    }

    /**
     * @throws DeserializationError
     */
    public function deserialize_len(): int
    {
        $value = $this->getLong();
        if ($value < 0) {
            throw new DeserializationError("Incorrect length value");
        }
        return $value;
    }

    public function deserialize_variant_index(): int
    {
        return $this->getInt();
    }

    public function check_that_key_slices_are_increasing($key1, $key2)
    {
        // Not required by the format.
    }

}