<?php namespace App\Service;


use App\Repository\AddressRepository;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\DistrictRepository;
use App\Repository\OrderRepository;
use function Symfony\Component\VarDumper\Dumper\esc;

class AddressService
{
    protected $addressRepository;
    protected $countryRepository;
    protected $cityRepository;
    protected $districtRepository;

    /**
     * OrderController constructor.
     * @param $orderRepository
     */
    public function __construct(
        AddressRepository   $addressRepository,
        CountryRepository   $countryRepository,
        CityRepository      $cityRepository,
        DistrictRepository  $districtRepository
    )
    {
        $this->addressRepository = $addressRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->districtRepository = $districtRepository;
    }

    public function createAddress($data){
        $customer = $data['customer'];
        $data = $data['address'];
        $data['customer'] = $customer;
        $country['name'] = $data['country_name'];
        $city_name = $data['city_name'];
        $district_name = $data['district_name'];

        try {
            $data['country'] = $this->countryRepository->findOrCreate($country);
            $data['city'] = $this->cityRepository->findOrCreate([
                    'name' => $city_name,
                    'country' => $data['country']]
            );
            $data['district'] = $this->districtRepository->findOrCreate([
                'name' => $district_name,
                'city' => $data['city']]
            );

            $address = $this->addressRepository->findOrCreate($data);
        }catch (\Exception $exception){
            throw new \Exception('Something bad'.$exception->getMessage());
        }
         return $address;
    }
}
