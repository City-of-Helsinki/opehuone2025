/* eslint-disable no-console,no-undef */
import Sortable from "sortablejs";

import { ownServices } from "../lib/ownServices";

export default {
  init() {
    // Own service functions
    ownServices();

    const el = document.getElementById("dock-settings-list");
    const moveupButton = jQuery('.dock-settings-list__move-up');
    const moveDownButton = jQuery('.dock-settings-list__move-down');

    const newDockListInput = jQuery('#new-dock-list');

    const sortedList = Sortable.create(el, {
      handle: '.dock-settings-list__drag',
      dragClass: "dock-settings-list__item--drag-item",
      animation: 150,
      dataIdAttr: "data-id",
      onSort: () => {
        const newList = sortedList.toArray();
        newDockListInput.val(newList.join());
      }
    });

    const moveElement = (element, direction) => {
      // bail out if we get input that we don't expect
      if (["up", "down"].includes(direction) === false ) {
        return false;
      }
      if (typeof(element.attr('data-id')) === 'undefined') {
        return false;
      }

      // `sortableId` is whatever you've set in your sortablejs config for `dataIdAttr`
      let sortableId = element.attr('data-id');
      let order = sortedList.toArray();
      let index = order.indexOf(sortableId);

      // pull the item we're moving out of the order
      order.splice(index, 1)

      // put it back in at the correct position
      if (direction === 'down') {
        order.splice(index+1, 0, sortableId);
      } else if (direction === 'up') {
        order.splice(index-1, 0, sortableId);
      }

      sortedList.sort(order, true);
      const newList = sortedList.toArray();
      newDockListInput.val(newList.join());
    }

    const clickUpHandler = () => {
      moveupButton.on('click', (e) => {
        e.preventDefault();
        const target = jQuery(e.currentTarget);
        moveElement(target.closest('.dock-settings-list__item'), 'up');
      });
    };

    const clickDownHandler = () => {
      moveDownButton.on('click', (e) => {
        e.preventDefault();
        const target = jQuery(e.currentTarget);
        moveElement(target.closest('.dock-settings-list__item'), 'down');
      });
    };

    const form = jQuery('#dock-settings');
    const submitBtn = jQuery('#settings-submit');

    form.submit(function (event) {
      submitBtn.addClass('is-disabled');
      event.preventDefault();
      const notifications = jQuery('#user-settings-notifications');
      notifications.html('Asetuksia päivitetään...');
      notifications.fadeIn('100');

      jQuery.ajax({
        url: opehuone_js.ajax_url,
        type: 'POST',
        data: ({
          action: 'update_user_dock',
          newDock: newDockListInput.val(),
          userId: opehuone_js.user_id
        }),
        success: function () {
          submitBtn.removeClass('is-disabled');

          setTimeout(() => {
            notifications.html('Asetukset päivitetty.');

            setTimeout(() => {
              notifications.fadeOut();
            }, 500);
          }, 100);
        }
      });
    });

    clickUpHandler();
    clickDownHandler();
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
