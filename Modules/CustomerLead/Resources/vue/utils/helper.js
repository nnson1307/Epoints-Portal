const LANGUAGE = JSON.parse(localStorage.getItem("tranlate"));

const Helper = {

    translate: (key) => {
        return LANGUAGE[key];
    },

    timeSince: (timeStamp, type = 'default') => {
        if (!(timeStamp instanceof Date)) {
            timeStamp = new Date(timeStamp);
        }
    
        if (isNaN(timeStamp.getDate())) {
            return "Invalid date";
        }
    
        var seconds = Math.floor((new Date() - timeStamp) / 1000);
        var interval = seconds / 31536000;
        
        switch(type){
            case 'today':
                if (interval > 1 || seconds / 2592000 > 1 || seconds / 86400 > 1) {
                    return moment(timeStamp).format('DD/MM/yyyy - hh:mm A');
                }
        
                interval = seconds / 3600;
                if (interval > 1) {
                    return Math.floor(interval) + " giờ trước";
                }
                interval = seconds / 60;
                if (interval > 1) {
                    return Math.floor(interval) + " phút trước";
                }
        
                return seconds > 0 ? Math.floor(seconds) + " giây trước" : 1 + " giây trước";  
            default:
                if (interval > 1) {
                    return Math.floor(interval) + " năm";
                }
                interval = seconds / 2592000;
                if (interval > 1) {
                    return Math.floor(interval) + " tháng";
                }
                interval = seconds / 86400;
                if (interval > 1) {
                return Math.floor(interval) + " ngày";
                }
        
        
                interval = seconds / 3600;
                if (interval > 1) {
                    return Math.floor(interval) + " giờ";
                }
                interval = seconds / 60;
                if (interval > 1) {
                    return Math.floor(interval) + " phút";
                }
                return seconds > 0 ? Math.floor(seconds) + " giây" : 1 + " giây";  
        }
    }
}

export default Helper;