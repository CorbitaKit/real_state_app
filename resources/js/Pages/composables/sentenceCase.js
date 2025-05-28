export const transformAddress = (str) => {
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

export const convertAddress = (property) => {

    return transformAddress()
}
