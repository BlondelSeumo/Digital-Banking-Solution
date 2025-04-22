var len = 0;
var clickLink = 0;
var search = null;
var process = false;

$('#searchInput').off('keydown').on('keydown', function (e) {
    var length = $('.search-list li').length;

    if (search !== $(this).val() && process) {
        len = 0;
        clickLink = 0;
        focusAndActivateLink(len);
    }

    // Down Key
    if (e.keyCode == 40 && length) {
        e.preventDefault(); // Prevent page from scrolling
        process = true;
        navigateList('down', length);
    }
    // Up Key
    else if (e.keyCode == 38 && length) {
        e.preventDefault(); // Prevent page from scrolling
        process = true;
        navigateList('up', length);
    }
    // Enter Key
    else if (e.keyCode == 13) {
        e.preventDefault();
        if ($(`.search-list li:eq(${clickLink}) a`).length && process) {
            $(`.search-list li:eq(${clickLink}) a`)[0].click();
        }
    }
    // Backspace Key
    else if (e.keyCode == 8) {
        resetNavigation();
        clearSearchResults();

        // Ensure the input retains focus
        $(this).focus();
    }
    search = $(this).val();

    // Keep the search input in focus
    $(this).focus();
});

$('#searchInput').off('input').on('input', function () {
    clearSearchResults();

    var query = $(this).val().trim();
    if (!query) {
        return;
    }

    var filteredData = filterSettings(query);
    renderSettings(filteredData, query);

    // Ensure the input retains focus after rendering results
    $(this).focus();
});

function focusAndActivateLink(index) {
    $(`.search-list li`).removeClass('active').find('a').removeClass('text-active');
    $(`.search-list li:eq(${index})`).addClass('active').find('a').addClass('text-active');
    $(`.search-list li:eq(${index}) a`).focus();
}

function navigateList(direction, length) {
    
    if (direction === 'down') {
        // Increment len and wrap around
        len = (len + 1) % length;
    } else if (direction === 'up') {
        // Decrement len and wrap around
        len = (len - 1 + length) % length;
    }

    // Ensure len is within bounds
    if (len >= length) {
        len = length - 1;
    }

   
    if (len < 0) {
        len = 0;
    }

    focusAndActivateLink(len-1);
    clickLink = len-1;
}




function resetNavigation() {
    len = 0;
    clickLink = 0;
    focusAndActivateLink(len);
}

function clearSearchResults() {
    $('.search-list').html('');
}

function filterSettings(query) {
    let filteredSettings = [];
    for (var key in settingsData) {
        if (settingsData.hasOwnProperty(key)) {
            var setting = settingsData[key];

            if (setting.submenu) {
                setting.submenu.forEach(subItem => {
                    var keywordMatch = subItem['keyword'].some(function (keyword) {
                        return keyword.toLowerCase().includes(query.toLowerCase());
                    });

                    var titleMatch = subItem['title'].toLowerCase().includes(query.toLowerCase());
                    if (keywordMatch || titleMatch) {
                        filteredSettings.push(subItem);
                    }
                });
            } else {
                var keywordMatch = setting.keyword.some(function (keyword) {
                    return keyword.toLowerCase().includes(query.toLowerCase());
                });

                var titleMatch = setting.title.toLowerCase().includes(query.toLowerCase());
                var subtitleMatch = null;

                if (setting.subtitle) {
                    subtitleMatch = setting.subtitle.toLowerCase().includes(query.toLowerCase());
                }

                if (keywordMatch || titleMatch || subtitleMatch) {
                    filteredSettings.push(setting);
                }
            }
        }
    }
    return filteredSettings;
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

function renderSettings(filteredSettings, query) {
    var search_result_pane = $('.search-list');
    if (isEmpty(filteredSettings)) {
        $(search_result_pane).append(getEmptyMessage());
    } else {
        for (const key in filteredSettings) {
            if (filteredSettings.hasOwnProperty(key)) {
                const element = filteredSettings[key];
                var setting = element;
                var url = null;
                var title = null;
                var tempRoute = null;
                var parent = null;

                tempRoute = setting.route_name;
                routeParams = null;
                if (setting.params) {
                    routeParams = setting.params;
                }
                title = setting.title;

                console.log(routeParams);
                

                if (Array.isArray(tempRoute)) {
                    for (let routeName of tempRoute) {
                        let resolvedUrl = resolveUrl(routeName, routeParams);
                        if (resolvedUrl) {
                            url = resolvedUrl;
                            break;
                        }
                    }
                } else {
                    url = resolveUrl(tempRoute, routeParams);
                }

                if (url) {
                    parent = resolveParent(url, setting.subtitle);
                    $(search_result_pane).append(`
                        <li>
                            <a class="search-list-link" href="${url}">
                                ${parent}
                                <p class="fw-bold text-color--3 d-block">${title}</p>
                            </a>
                        </li>
                    `);
                }
            }
        }
    }
}

function resolveUrl(tempRoute, routeParams) {
    
    for (let route of routes) {
        
        if (tempRoute in route) {
            let url = route[tempRoute];
            
            if (routeParams) {
                $.each(routeParams, function (paramKey, paramValue) {
                    console.log(paramKey);
                    
                    url = url.replace(new RegExp(`{${paramKey}\\??}`, 'g'), paramValue);
                });
            }
            url = url.replace(/\/+$/, '');

            return url
        }
    }
    return null;
}

function resolveParent(url, subtitle) {
    var link = $(`.sidebar__menu a[href='${url}']`);
    var text = $(link).parents('.sidebar-menu-item.sidebar-dropdown').find('.menu-title').first().text();
    return text || (subtitle ? 'System Setting' : 'Main Menu');
}

function getEmptyMessage() {
    return `<li><p>No results found</p></li>`;
}

