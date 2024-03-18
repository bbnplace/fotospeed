
export async function loadRecords({page, itemsPerPage, sortBy}){
    const payload = {page, itemsPerPage, sortBy, search}
    loading = true;
    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.post(usePage().props.endpoint, payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });
    loadedRecords.value = response.data.records;
    totalRecords.value = response.data.totalRecords
    loading = false;
}
