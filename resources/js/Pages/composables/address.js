import { regions, provinces, cities, barangays } from 'select-philippines-address';

export const generateRegion = async () => {
    try {
        const response = await regions();
        return response;
    } catch (error) {
        console.error('Error fetching regions:', error);
        return [];
    }
}

export const handleProvince = async (region_code) => {
    try {
        const response = await provinces(region_code);
        return response;
    } catch (error) {
        console.error('Error fetching provinces:', error);
        return [];
    }
}

export const handleCity = async (province_code) => {
    try {
        const response = await cities(province_code);
        return response;
    } catch (error) {
        console.error('Error fetching cities:', error);
        return [];
    }
}

export const handleBarangay = async (city_code) => {
    try {
        const response = await barangays(city_code);
        return response;
    } catch (error) {
        console.error('Error fetching barangays:', error);
        return [];
    }
}
