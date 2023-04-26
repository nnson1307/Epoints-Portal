
const Helper = {
    timeSince: (timeStamp, type = 'default', translate = {}) => {
        if (!(timeStamp instanceof Date)) {
            timeStamp = new Date(timeStamp);
        }

        let language = {
            ago: translate['Trước'] || 'Trước',
            hours: translate['Giờ'] || 'Giờ',
            minute: translate['Phút'] || 'Phút',
            second: translate['Giây'] || 'Giây',
            day: translate['Ngày'] || 'Ngày',
            month: translate['Tháng'] || 'Tháng',
            year: translate['Năm'] || 'Năm',
        }

        console.log({language});
    
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
                    return Math.floor(interval) + ` ${language.hours} ${language.ago}`;
                }
                interval = seconds / 60;
                if (interval > 1) {
                    return Math.floor(interval) + ` ${language.minute} ${language.ago}`;
                }
        
                return seconds > 0 ? Math.floor(seconds) + ` ${language.second} ${language.ago}` : 1 + ` ${language.second} ${language.ago}`;  
            default:
                if (interval > 1) {
                    return Math.floor(interval) + ` ${language.year}`;
                }
                interval = seconds / 2592000;
                if (interval > 1) {
                    return Math.floor(interval) + ` ${language.month}`;
                }
                interval = seconds / 86400;
                if (interval > 1) {
                return Math.floor(interval) + ` ${language.day}`;
                }
        
        
                interval = seconds / 3600;
                if (interval > 1) {
                    return Math.floor(interval) + ` ${language.hours}`;
                }
                interval = seconds / 60;
                if (interval > 1) {
                    return Math.floor(interval) + ` ${language.minute}`;
                }
                return seconds > 0 ? Math.floor(seconds) + ` ${language.second}` : 1 + ` ${language.second}`;  
        }
    }
}

export default Helper;