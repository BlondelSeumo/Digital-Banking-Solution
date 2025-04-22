(function () {
    'use strict';

    const table = $('#viserTable');
    const modal = $('#exportModal');

    const tableColumns = table.find('thead th[data-key]').map((index, th) => ({
        key: $(th).data('key'),
        value: $(th).text()
    })).get();

    const exportButtonClickHandler = () => {
        const container = modal.find('.modal-body .columns-container');

        container.empty();

        tableColumns.forEach((element, index) => {
            const inputOption = `
            <div class="form-check">
                <input class="form-check-input" name="selected_columns" type="checkbox" value="${element.key}" id="option-${index}" checked>
                <label class="form-check-label" for="option-${index}">${element.value}</label>
            </div>
        `;
            container.append(inputOption);
        });

        fontSizeChangeHandler();
        modal.modal('show');
    }

    const tableHeadings = table.find('thead th').map((index, th) => ({
        index,
        key: $(th).data('key')
    })).get();

    const exportFormSubmitHandler = (e) => {
        e.preventDefault();

        const form = $('#tableExportForm');

        const exportableKeys = form.find('[name=selected_columns]:checked').map(function () {
            return this.value;
        }).get();

        const exportableColumn = tableColumns.filter(element => exportableKeys.includes(element.key));
        const exportHeader = exportableColumn.map(element => element.value);

        let exportData = [];

        table.find('tbody tr').each((i, tr) => {
            const row = [];

            const allColumns = $(tr).find('td');

            exportableColumn.forEach((item) => {
                let index = tableHeadings.find(th => th.key == item.key).index;
                row.push(allColumns[index].innerText.trim());
            })

            exportData.push(row);
        });

        const exportType = $('#tableExportForm [name=export_type]').val();

        if (exportType === 'excel') {
            exportToExcel(exportHeader, exportData);
        } else if (exportType === 'csv') {
            exportToCsv(exportHeader, exportData);
        } else {
            const formData = new FormData(e.target);
            const config = Object.fromEntries(formData.entries());

            if (exportType === 'pdf') {
                exportToPdf(exportHeader, exportData, config);
            } else {
                printData(exportHeader, exportData, config);
            }
        }
    }

    const orderByChangeHandler = (e) => window.location.href = prepareUrl(e.currentTarget.name, e.currentTarget.value);

    const perPageChangeHandler = (e) => window.location.href = prepareUrl('per_page', e.currentTarget.value);

    const exportTypeChangeHandler = (e) => {

        const type = modal.find('[name=export_type]').val();

        if (type === 'pdf' || type === 'print') {
            modal.find('.pdf-configuration').removeClass('d-none');
        } else {
            modal.find('.pdf-configuration').addClass('d-none');
        }
    }

    const fontSizeChangeHandler = () => {
        const size = modal.find('[name=font_size]').val();
        const textElement = modal.find('.sample-text')[0];
        textElement.style.fontSize = `${size}px`;
        textElement.innerText = `This is a sample text and the font size is ${size}px`;
    }

    const orientationChangeHandler = (e) => {
        const orientation = e.currentTarget.value;
        const element = modal.find('.orientation-sample');
        if (orientation == 'portrait') {
            element.removeClass('landscape');
        } else {
            element.removeClass('portrait');
        }
        element.addClass(orientation);

    }

    $('#exportBtn').on('click', exportButtonClickHandler);
    $('#tableExportForm').on('submit', exportFormSubmitHandler);
    $('[name=per_page]').on('change', perPageChangeHandler);
    $('.order-data').on('change', orderByChangeHandler);

    modal.on('change', '[name=export_type]', exportTypeChangeHandler);
    modal.on('input', '[name=font_size]', fontSizeChangeHandler);
    modal.on('change', '[name=orientation]', orientationChangeHandler);

    function exportToExcel(header, data) {
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([header, ...data]);
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        XLSX.writeFile(wb, `${getFileName()}.xlsx`);
    }

    function exportToCsv(header, data) {
        const csvContent = [header.join(','), ...data.map(row => row.map(value => `"${value}"`).join(','))].join('\n');
        downloadCSV(csvContent, `${getFileName()}.csv`);
    }

    function downloadCSV(csv, filename) {
        const csvFile = new Blob([csv], { type: 'text/csv' });
        const downloadLink = document.createElement('a');

        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

    function exportToPdf(header, data, config) {
        const doc = makePDF(header, data, config);
        doc.save(`${getFileName()}.pdf`);
    }

    function makePDF(header, data, config) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF(
            {
                orientation: config.orientation ?? 'landscape',
                unit: 'pt',
                format: config.page_size ?? 'a4'
            }
        );

        let marginTop = 25;
        let pageWidth = doc.internal.pageSize.width;
        let headerText = config.heading;
        let textWidth = doc.getStringUnitWidth(headerText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
        let textX = (pageWidth - textWidth) / 2; // Calculate X position for centering
        doc.text(headerText, textX, marginTop); // Adjust the Y coordinate as needed


        doc.autoTable({
            head: [header],
            body: data,
            theme: 'grid',
            styles: {
                fontSize: config.font_size ?? '12'
            },
            headStyles: {
                fillColor: config.heading_color ?? '#4634ff'
            }
        });

        return doc;
    }

    function printData(header, data, config) {
        const doc = makePDF(header, data, config);
        doc.autoPrint();
        window.open(doc.output('bloburl'));
    }

    function prepareUrl(query, value) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set(query, value);
        return cleanUpAndDecodeURL(currentUrl.toString());
    }

    function cleanUpAndDecodeURL(url) {
        let parsedURL = new URL(url);
        let params = parsedURL.searchParams;
        let cleanedParams = new URLSearchParams();

        params.forEach((value, key) => {
            if (value) {
                cleanedParams.append(key, value);
            }
        });

        let cleanedURL = `${parsedURL.origin}${parsedURL.pathname}`;
        if (cleanedParams.toString()) {
            cleanedURL += `?${decodeURIComponent(cleanedParams.toString()).replace(/%5B/g, '[').replace(/%5D/g, ']')}`;
        }

        return cleanedURL;
    }

    function getFileName() {
        const fileNameBase = $('#tableExportForm').find('[name=file_name]').val();
        const timestamp = new Date().toISOString().replace(/[:.-]/g, '_').replace('T', '_').replace('Z', '');
        return `${fileNameBase}_${timestamp}`;
    }

    $(table).find('.dropdown-menu').each((index, element) => {
        if (!element.childElementCount) {
            $(element).parents('.dropdown').remove();
        }
    });

    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        let url = new URL(window.location.href);
        let searchParams = url.searchParams;
        let fields = document.querySelector('#filterForm').elements;

        Array.from(fields).forEach(field => {
            if (field.name) {
                if (field.value) {
                    searchParams.set(field.name, field.value);
                } else {
                    searchParams.delete(field.name);
                }
            }
        });

        window.location.href = cleanUpAndDecodeURL(url.toString());
    });


    $('.clearOrderBy').on('click', function () {
        let url = new URL(window.location.href);
        let searchParams = url.searchParams;
        let fields = [{ name: 'order_by_column' }, { name: 'order_by' }];

        Array.from(fields).forEach(field => {
            if (field.name) {
                if (field.value) {
                    searchParams.set(field.name, field.value);
                } else {
                    searchParams.delete(field.name);
                }
            }
        });

        window.location.href = cleanUpAndDecodeURL(url.toString());
    });

    const initializeDatePicker = (element) => {
        const calculateDrops = () => {
            const inputOffset = $(element).offset();
            const inputHeight = $(element).outerHeight();
            const windowHeight = $(window).height();
            const spaceBelow = windowHeight - (inputOffset.top + inputHeight);
            const spaceAbove = inputOffset.top;
            return spaceBelow > 300 ? 'down' : spaceAbove > 300 ? 'up' : 'auto';
        };

        $(element).daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            showDropdowns: true,

            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            parentEl: element.closest('.filter-field'),
            drops: calculateDrops(),
            isInvalidDate: function (date) {
                const picker = $(element).data('daterangepicker');
                if (!picker.startDate) {
                    return false;
                }
                const startDate = picker.startDate;
                const maxEndDate = startDate.clone().add(1, 'years');
                return date.isAfter(maxEndDate);
            }
        });

        const changeDatePickerText = (event, startDate, endDate) => {
            $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
        };

        $(element).on('apply.daterangepicker', (event, picker) => {
            changeDatePickerText(event, picker.startDate, picker.endDate);
        });

        if ($(element).val()) {
            let dateRange = $(element).val().split(' - ');
            let startDate = moment(new Date(dateRange[0]));
            let endDate = moment(new Date(dateRange[1]));
            $(element).data('daterangepicker').setStartDate(startDate);
            $(element).data('daterangepicker').setEndDate(endDate);
            $(element).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
        }
    };

    $('.date-range').on('focus click', function () {
        if (!$(this).data('daterangepicker')) {
            initializeDatePicker(this);
        }
    });

    $('#clearFilter').on('click', function () {
        let url = new URL(window.location.href);
        let searchParams = url.searchParams;

        let fields = document.querySelector('#filterForm').elements;

        Array.from(fields).forEach(field => {
            if (field.name && searchParams.has(field.name)) {
                searchParams.delete(field.name);
            }
        });

        window.location = url.toString();
    });

})();