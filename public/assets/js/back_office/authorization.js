function authorization(del) {
	var headers = {
        'Accept-Language': language,
        'Authorization': 'Bearer '+localStorage.getItem("travelesttAccess")
    }  
    if(del) {
    	headers['X-CSRF-Token'] = $('meta[name="csrf-token"').attr('content')
    }
	return headers 
}