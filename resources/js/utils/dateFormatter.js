import dayjs from 'dayjs';

export const getFormattedDateTime = (date) => {
	return date && dayjs(date).format('L LT');
};

export const getFormattedDate = (date) => {
	return date && dayjs(date).format('L');
};

export const getISODate = (date) => {
	return date && dayjs(date).format('YYYY-MM-DDTHH:mm');
};

export const getRelativeDate = (date) => {
	return date && dayjs().to(dayjs(date));
};
