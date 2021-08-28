
export const hasPermission = (permissions, checkPermissionName) => {

    if (!permissions.length) {
        return false;
    }

    const canShow = permissions.some(element => { return element.name == checkPermissionName; });

    return canShow;
}
