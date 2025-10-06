export function getDisplayName(viewer, listedUser) {
    if (!viewer || !listedUser) return "Unknown";

    if(viewer.id == listedUser.id) return `${listedUser.eesnimi} ${listedUser.perenimi}`;
    const viewerRoles = viewer.role.split(",").map(r => r.trim());
    const listedRoles = listedUser.role.split(",").map(r => r.trim());

    // siin kontektis privileged = admin või õpetaja
    const viewerIsPrivileged = viewerRoles.includes("admin") || viewerRoles.includes("teacher");
    const listedIsPrivileged = listedRoles.includes("admin") || listedRoles.includes("teacher");

    if (listedIsPrivileged) {
        return `${listedUser.eesnimi} ${listedUser.perenimi}`;
    }

    if (!viewerIsPrivileged) {
        return listedUser.public_name || "Anonüümne";
    }

    return `${listedUser.eesnimi} ${listedUser.perenimi}`;
}


export function showPublicName(viewer, listedUser) {
    if (!viewer || !listedUser) return false;

    const viewerRoles = viewer.role.split(",").map(r => r.trim());
    const listedRoles = listedUser.role.split(",").map(r => r.trim());

    const viewerIsPrivileged = viewerRoles.includes("admin") || viewerRoles.includes("teacher");
    const listedIsPrivileged = listedRoles.includes("admin") || listedRoles.includes("teacher");

    if (listedIsPrivileged) return false;

    return !viewerIsPrivileged && viewer.id !== listedUser.id;
}

export function showFirstName(viewer, listedUser){
    // return first name if it is allowed, otherwise the public name
    if(showPublicName(viewer, listedUser)){
        return listedUser.public_name;
    }else{
        return listedUser.eesnimi;
    }
}