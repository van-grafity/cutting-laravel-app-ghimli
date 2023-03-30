

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

    let fetch_data = {
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        redirect: "follow",
        referrerPolicy: "no-referrer",
    };

    if(method === "GET") {
        query_string = new URLSearchParams(data).toString();
        url = url + "?" + query_string

        fetch_data.method = method;
        fetch_data.headers = {
            "Content-Type": "application/json",
        };
    }

    if(method === "DELETE") {
        fetch_data.method = method;
        fetch_data.headers = {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": data.token,
        };
    }

    if(method === "PUT") {
        fetch_data.method = method;
        fetch_data.headers = {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": data.token,
        };

        fetch_data.body = JSON.stringify(data.body);
    }

    if(method === "POST") {
        fetch_data.method = method;
        fetch_data.headers = {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": data.token,
        };

        fetch_data.body = JSON.stringify(data.body);
    }

    const response = await fetch(url, fetch_data);
    return response.json();
}

// ## Sweetalert2 Manager
const swal_delete_confirm = (data = {}) => {
    const swalComponent = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger m-2",
            cancelButton: "btn btn-secondary m-2",
        },
        buttonsStyling: false,
    });

    let title = data.title ? data.title : "Are you sure?";
    let confirm_button = data.confirm_button ? data.confirm_button : "Delete";
    let success_message = data.success_message
        ? data.success_message
        : "Deleted!";
    let failed_message = data.failed_message
        ? data.failed_message
        : "Cancel Delete";

    return new Promise((resolve, reject) => {
        swalComponent
            .fire({
                title: title,
                text: data.text,
                confirmButtonText: confirm_button,
                icon: "warning",
                showCancelButton: true,
                reverseButtons: true,
            })
            .then((result) => {
                if (result.isConfirmed) {
                    resolve(true);
                }
                resolve(false);
            })
            .catch((error) => {
                reject(error);
            });
    });
}

const swal_info = (data = { title: "Success", option: false }) => {
    const afterClose = () => {
        if( data.reload_option == true ) {
            location.reload();
        } else {
            return false;
        }
    }
    Swal.fire({
        icon: "success",
        title: data.title,
        showConfirmButton: false,
        timer: 3000,
        didClose: afterClose,
    });
};

const swal_failed = (data) => {
    Swal.fire({
        icon: "error",
        title: data.title ? data.title : "Something Error",
        text: 'Please contact the Administrator',
        showConfirmButton: true,
    });
}

const swal_warning = (data) => {
    Swal.fire({
        icon: "warning",
        title: data.title ? data.title : "Caution!",
        text:  data.text ? data.text : null,
        showConfirmButton: true,
    });
}

const show_flash_message = ( session = {} ) => {
    if ("success" in session) {
        Swal.fire({
            icon: "success",
            title: session.success,
            showConfirmButton: false,
            timer: 3000,
        });
    }
    if ("error" in session) {
        Swal.fire({
            icon: "error",
            title: session.error,
            confirmButtonColor: "#007bff",
        });
    }
}