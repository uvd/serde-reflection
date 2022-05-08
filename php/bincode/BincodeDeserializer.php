<?php


class BincodeDeserializer extends BinaryDeserializer
{

    public function deserialize_f32(): float
    {
        return getFloat();
    }

    public function deserialize_f64(): float
    {
        return getDouble();
    }

    /**
     * @throws DeserializationError
     */
    public function deserialize_len(): int
    {
        $value = getLong();
        if ($value < 0) {
            throw new DeserializationError("Incorrect length value");
        }
        return $value;
    }

    public function deserialize_variant_index(): int
    {
        return getInt();
    }

    public function check_that_key_slices_are_increasing($key1, $key2)
    {
        // Not required by the format.
    }

}