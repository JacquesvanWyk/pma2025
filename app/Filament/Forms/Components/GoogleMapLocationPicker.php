<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

class GoogleMapLocationPicker extends Field
{
    protected string $view = 'filament.forms.components.google-map-location-picker';

    protected string|Closure|null $latitudeField = 'latitude';

    protected string|Closure|null $longitudeField = 'longitude';

    protected string|Closure|null $cityField = 'city';

    protected string|Closure|null $provinceField = 'province';

    protected string|Closure|null $countryField = 'country';

    protected int|Closure $defaultZoom = 5;

    protected int|Closure $selectedZoom = 12;

    protected array|Closure $defaultCenter = ['lat' => -29.0, 'lng' => 24.0];

    protected string|Closure $height = '400px';

    public function latitudeField(string|Closure|null $field): static
    {
        $this->latitudeField = $field;

        return $this;
    }

    public function longitudeField(string|Closure|null $field): static
    {
        $this->longitudeField = $field;

        return $this;
    }

    public function cityField(string|Closure|null $field): static
    {
        $this->cityField = $field;

        return $this;
    }

    public function provinceField(string|Closure|null $field): static
    {
        $this->provinceField = $field;

        return $this;
    }

    public function countryField(string|Closure|null $field): static
    {
        $this->countryField = $field;

        return $this;
    }

    public function defaultZoom(int|Closure $zoom): static
    {
        $this->defaultZoom = $zoom;

        return $this;
    }

    public function selectedZoom(int|Closure $zoom): static
    {
        $this->selectedZoom = $zoom;

        return $this;
    }

    public function defaultCenter(array|Closure $center): static
    {
        $this->defaultCenter = $center;

        return $this;
    }

    public function height(string|Closure $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getLatitudeField(): ?string
    {
        return $this->evaluate($this->latitudeField);
    }

    public function getLongitudeField(): ?string
    {
        return $this->evaluate($this->longitudeField);
    }

    public function getCityField(): ?string
    {
        return $this->evaluate($this->cityField);
    }

    public function getProvinceField(): ?string
    {
        return $this->evaluate($this->provinceField);
    }

    public function getCountryField(): ?string
    {
        return $this->evaluate($this->countryField);
    }

    public function getDefaultZoom(): int
    {
        return $this->evaluate($this->defaultZoom);
    }

    public function getSelectedZoom(): int
    {
        return $this->evaluate($this->selectedZoom);
    }

    public function getDefaultCenter(): array
    {
        return $this->evaluate($this->defaultCenter);
    }

    public function getHeight(): string
    {
        return $this->evaluate($this->height);
    }
}
