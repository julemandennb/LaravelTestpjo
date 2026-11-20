export default  (datostr) => {

    if(datostr != undefined)
    {
        const date = new Date(datostr); // Convert the ISO string to a Date object

        // Extract the components
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();

        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        // Format as dd/MM/yyyy HH:mm
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }
    else
        return "-"
};