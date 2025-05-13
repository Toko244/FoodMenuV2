import React from "react";
import Select from "react-select";
import makeAnimated from "react-select/animated";

const animatedComponents = makeAnimated();

export default function AnimatedMulti({ options, defaultValue, onChange }) {
    return (
        <Select
            closeMenuOnSelect={false}
            components={animatedComponents}
            defaultValue={defaultValue}
            isMulti
            options={options}
            onChange={onChange}
        />
    );
}
