import { reactive } from  'vue';

let snackbarRef = 0;

export const snackbarOption = reactive({
    id: snackbarRef++,
    show: false,
});

export const showSnackbar = text => {
    snackbarOption.text = text;
    snackbarOption.show = true;
    snackbarOption.id = snackbarRef++;
}

export const hideSnackbar = () => {
    snackbarOption.text = "";
    snackbarOption.show = false;
}

