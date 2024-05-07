

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
        timer: 2000,
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

const clear_form = (data) => {
    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        data = {
            modal_id : 'packinglist_modal',
            title: "Add Product",
            btn_submit: "Add Product",
            form_action_url: "",
        };
        * --------------------------------------------------------------------
    */

    $(`#${data.modal_id} .modal-title`).text(data.title);
    $(`#${data.modal_id} .btn-submit`).text(data.btn_submit);
    $(`#${data.modal_id} form`).attr(`action`, data.form_action_url);
    $(`#${data.modal_id} form`).find("input[type=text], input[type=number], input[type=email], input[type=hidden], input[type=password], textarea").val("");
    $(`#${data.modal_id} form`).find(`select`).val("").trigger(`change`);
    $(`#${data.modal_id} form`).find('input,select').removeClass("is-invalid");
    $(`#${data.modal_id} form`).find('span.invalid-feedback').css('display', 'none');
}


const using_fetch_v2 = async ({ url = "", data = {}, method = "GET", token = null }) => {

    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        data = {
            url: "https://example.com/api",
            method: "POST",
            data: { key: "value" },
            token: "your_token",
        }
        * --------------------------------------------------------------------
    */

    const headers = {
        "Content-Type": "application/json",
    };

    const fetchOptions = {
        method,
        headers,
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        redirect: "follow",
        referrerPolicy: "no-referrer",
    };

    if (["GET"].includes(method)) {
        const queryString = new URLSearchParams(data).toString();
        url = `${url}?${queryString}`;
    }

    if (["POST", "PUT", "DELETE"].includes(method)) {
        headers["X-CSRF-TOKEN"] = token;
        fetchOptions.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, fetchOptions);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    } catch (error) {
        console.error("Fetch error:", error);
        throw error;
    }
}


const swal_confirm = (options = {}) => {
    /*
     * Example usage of swalConfirm:
     * --------------------------------------------------------------------
     * const result = await swalConfirm({
     *     title: "Custom Title",
     *     text: "Custom Text",
     *     icon: "info",
     *     confirmButtonText: "Save",  // Use confirmButtonText for clarity
     *     confirmButtonClass: "btn-primary",
     *     cancelButtonClass: "btn-secondary"
     * });
     * --------------------------------------------------------------------
     */

    // Merge user options with default configurations
    const mergedOptions = {
        title: options.title || "Are you sure?",
        text: options.text,
        icon: options.icon || "question",
        confirmButtonText: options.confirmButton || "Save",
        showCancelButton: true,
        reverseButtons: true,
        customClass: {
            confirmButton: `btn ${options.confirmButtonClass || "btn-primary"} m-2`,
            cancelButton: `btn ${options.cancelButtonClass || "btn-secondary"} m-2`,
        },
        buttonsStyling: false
    };

    // Create a SweetAlert2 instance with merged options
    return Swal.fire(mergedOptions)
        .then(result => result.isConfirmed ? Promise.resolve(true) : Promise.resolve(false))
        .catch(error => Promise.reject(error));
}



