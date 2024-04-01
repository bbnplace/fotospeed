import { reactive } from  'vue';

let snackbarRef = 0;

export const snackbarOption = reactive({
    id: snackbarRef++,
    show: false,
    text: ""
});

export const showSnackbar = text => {
    snackbarOption.text = text;
    snackbarOption.show = true;
    snackbarOption.id = snackbarRef++;
}


