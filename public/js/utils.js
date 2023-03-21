

async function delete_using_fetch(url = "", data = {}) {
    const response = await fetch(url, {
        method: "DELETE",
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            'X-CSRF-TOKEN': data.token
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
    });
    return response.json();
}

async function get_using_fetch(url = "", data = {}) {
    const response = await fetch(url, {
        method: "GET",
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
    });
    return response.json();
}

async function using_fetch(url = "", data = {}, method = "GET") {

    if(method === "GET") {
        query_string = new URLSearchParams(data).toString();
        url = url + "?" + query_string
        headers = {
            "Content-Type": "application/json",
        };
    }

    if(method === "DELETE") {
        headers = {
            "Content-Type": "application/json",
            'X-CSRF-TOKEN': data.token
        };
    }
    
    const response = await fetch(url, {
        method: method,
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        headers: headers,
        redirect: "follow",
        referrerPolicy: "no-referrer",
    });
    return response.json();
}