import { Notification } from 'element-ui';

export const notifications = (request) => {

    if (request.status == 'success') {
        Notification.success({
            title: '',
            message: request.message,
            offset: 100,
        });
    } else if(request.status == 'error') {
        Notification.error({
            title: '',
            message: request.message,
            offset: 100,
        });
    }

}