@extends('layout.app')
@section('title', 'Grupos')

@section('style')
<style>
    :root {
        --mdb-datepicker-backdrop-background-color: rgba(0, 0, 0, 0.4);
        --mdb-datepicker-zindex: 1065;
        --mdb-datepicker-container-zindex: 1066;
        --mdb-datepicker-toggle-right: -10px;
        --mdb-datepicker-toggle-top: 50%;
        --mdb-datepicker-toggle-focus-color: #3b71ca;
    }

    .datepicker-toggle-button {
        position: absolute;
        outline: none;
        border: none;
        background-color: rgba(0, 0, 0, 0);
        right: var(--mdb-datepicker-toggle-right);
        top: var(--mdb-datepicker-toggle-top);
        transform: translate(-50%, -50%)
    }

    .datepicker-toggle-button:focus {
        color: var(--mdb-datepicker-toggle-focus-color)
    }

    .datepicker-toggle-button:hover {
        color: var(--mdb-datepicker-toggle-focus-color)
    }

    .datepicker-backdrop {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: var(--mdb-datepicker-backdrop-background-color);
        z-index: var(--mdb-datepicker-zindex)
    }

    .datepicker-dropdown-container {
        --mdb-datepicker-dropdown-container-width: 328px;
        --mdb-datepicker-dropdown-container-height: 380px;
        --mdb-datepicker-dropdown-container-background-color: var(--mdb-surface-bg);
        --mdb-datepicker-dropdown-container-border-radius: 0.5rem;
        --mdb-datepicker-dropdown-container-box-shadow: 0 2px 15px -3px rgba(var(--mdb-box-shadow-color-rgb), 0.07), 0 10px 20px -2px rgba(var(--mdb-box-shadow-color-rgb), 0.04);
        width: var(--mdb-datepicker-dropdown-container-width);
        height: var(--mdb-datepicker-dropdown-container-height);
        background-color: var(--mdb-datepicker-dropdown-container-background-color);
        border-radius: var(--mdb-datepicker-dropdown-container-border-radius);
        box-shadow: var(--mdb-datepicker-dropdown-container-box-shadow);
        z-index: var(--mdb-datepicker-container-zindex)
    }

    .datepicker-modal-container {
        --mdb-datepicker-modal-container-transform: translate(-50%, -50%);
        --mdb-datepicker-modal-container-width: 328px;
        --mdb-datepicker-modal-container-height: 512px;
        --mdb-datepicker-modal-container-background-color: var(--mdb-surface-bg);
        --mdb-datepicker-modal-container-border-radius: 0.6rem 0.6rem 0.5rem 0.5rem;
        --mdb-datepicker-modal-container-box-shadow: 0 2px 15px -3px rgba(var(--mdb-box-shadow-color-rgb), 0.07), 0 10px 20px -2px rgba(var(--mdb-box-shadow-color-rgb), 0.04);
        --mdb-datepicker-modal-container-date-media-margin-top: 100px;
        --mdb-datepicker-modal-container-day-cell-media-width: 32px;
        --mdb-datepicker-modal-container-day-cell-media-height: 32px;
        --mdb-datepicker-modal-container-media-width: 475px;
        --mdb-datepicker-modal-container-media-height: 360px;
        --mdb-datepicker-header-border-radius-landscape: 0.5rem 0 0 0.5rem;
        --mdb-datepicker-header-height: 120px;
        --mdb-datepicker-header-padding-x: 24px;
        --mdb-datepicker-header-background-color: var(--mdb-picker-header-bg);
        --mdb-datepicker-header-border-radius: 0.5rem 0.5rem 0 0;
        --mdb-datepicker-title-height: 32px;
        --mdb-datepicker-title-text-font-size: 10px;
        --mdb-datepicker-title-text-font-weight: 400;
        --mdb-datepicker-title-text-letter-spacing: 1.7px;
        --mdb-datepicker-title-text-color: #fff;
        --mdb-datepicker-date-height: 72px;
        --mdb-datepicker-date-text-font-size: 34px;
        --mdb-datepicker-date-text-font-weight: 400;
        --mdb-datepicker-date-text-color: #fff;
        --mdb-datepicker-footer-height: 56px;
        --mdb-datepicker-footer-padding-x: 12px;
        --mdb-datepicker-footer-btn-background-color: var(--mdb-surface-bg);
        --mdb-datepicker-footer-btn-color: var(--mdb-surface-color);
        --mdb-datepicker-footer-btn-disabled-color: rgba(var(--mdb-surface-color-rgb), 0.5);
        --mdb-datepicker-footer-btn-padding-x: 10px;
        --mdb-datepicker-footer-btn-font-size: 0.8rem;
        --mdb-datepicker-footer-btn-font-weight: 500;
        --mdb-datepicker-footer-btn-height: 40px;
        --mdb-datepicker-footer-btn-line-height: 40px;
        --mdb-datepicker-footer-btn-letter-spacing: 0.1rem;
        --mdb-datepicker-footer-btn-border-radius: 10px;
        --mdb-datepicker-footer-btn-margin-bottom: 10px;
        --mdb-datepicker-footer-btn-state-background-color: var(--mdb-highlight-bg-color);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: var(--mdb-datepicker-modal-container-transform);
        width: var(--mdb-datepicker-modal-container-width);
        height: var(--mdb-datepicker-modal-container-height);
        background-color: var(--mdb-datepicker-modal-container-background-color);
        border-radius: var(--mdb-datepicker-modal-container-border-radius);
        box-shadow: var(--mdb-datepicker-modal-container-box-shadow);
        z-index: var(--mdb-datepicker-container-zindex)
    }

    @media screen and (min-width: 320px)and (max-width: 820px)and (orientation: landscape) {
        .datepicker-modal-container .datepicker-header {
            height: 100%
        }
    }

    @media screen and (min-width: 320px)and (max-width: 820px)and (orientation: landscape) {
        .datepicker-modal-container .datepicker-date {
            margin-top: var(--mdb-datepicker-modal-container-date-media-margin-top)
        }
    }

    @media screen and (min-width: 320px)and (max-width: 820px)and (orientation: landscape) {
        .datepicker-modal-container {
            flex-direction: row;
            width: var(--mdb-datepicker-modal-container-media-width);
            height: var(--mdb-datepicker-modal-container-media-height)
        }

        .datepicker-modal-container .datepicker-day-cell {
            width: var(--mdb-datepicker-modal-container-day-cell-media-width);
            height: var(--mdb-datepicker-modal-container-day-cell-media-height)
        }
    }

    .datepicker-header {
        height: var(--mdb-datepicker-header-height);
        padding-right: var(--mdb-datepicker-header-padding-x);
        padding-left: var(--mdb-datepicker-header-padding-x);
        background-color: var(--mdb-datepicker-header-background-color);
        display: flex;
        flex-direction: column;
        border-radius: var(--mdb-datepicker-header-border-radius)
    }

    @media screen and (min-width: 320px)and (max-width: 820px)and (orientation: landscape) {
        .datepicker-header {
            border-radius: var(--mdb-datepicker-header-border-radius-landscape)
        }
    }

    .datepicker-title {
        height: var(--mdb-datepicker-title-height);
        display: flex;
        flex-direction: column;
        justify-content: flex-end
    }

    .datepicker-title-text {
        font-size: var(--mdb-datepicker-title-text-font-size);
        font-weight: var(--mdb-datepicker-title-text-font-weight);
        text-transform: uppercase;
        letter-spacing: var(--mdb-datepicker-title-text-letter-spacing);
        color: var(--mdb-datepicker-title-text-color)
    }

    .datepicker-date {
        height: var(--mdb-datepicker-date-height);
        display: flex;
        flex-direction: column;
        justify-content: flex-end
    }

    .datepicker-date-text {
        font-size: var(--mdb-datepicker-date-text-font-size);
        font-weight: var(--mdb-datepicker-date-text-font-weight);
        color: var(--mdb-datepicker-date-text-color)
    }

    .datepicker-main {
        --mdb-datepicker-date-controls-padding-top: 10px;
        --mdb-datepicker-date-controls-padding-x: 12px;
        --mdb-datepicker-date-controls-color: rgba(0, 0, 0, 0.64);
        --mdb-datepicker-view-change-button-padding: 10px;
        --mdb-datepicker-view-change-button-color: var(--mdb-surface-color);
        --mdb-datepicker-view-change-button-disabled-color: rgba(var(--mdb-surface-color-rgb), 0.5);
        --mdb-datepicker-view-change-button-font-weight: 500;
        --mdb-datepicker-view-change-button-font-size: 0.9rem;
        --mdb-datepicker-view-change-button-border-radius: 10px;
        --mdb-datepicker-view-change-button-state-background-color: var(--mdb-highlight-bg-color);
        --mdb-datepicker-view-change-button-after-border-width: 5px;
        --mdb-datepicker-view-change-button-after-margin-left: 5px;
        --mdb-datepicker-arrow-controls-margin-top: 10px;
        --mdb-datepicker-previous-button-width: 40px;
        --mdb-datepicker-previous-button-height: 40px;
        --mdb-datepicker-previous-button-line-height: 40px;
        --mdb-datepicker-previous-button-color: var(--mdb-surface-color);
        --mdb-datepicker-previous-button-disabled-color: rgba(var(--mdb-surface-color-rgb), 0.5);
        --mdb-datepicker-previous-button-margin-right: 24px;
        --mdb-datepicker-previous-button-state-background-color: var(--mdb-highlight-bg-color);
        --mdb-datepicker-previous-button-state-border-radius: 50%;
        --mdb-datepicker-previous-button-after-margin: 15.5px;
        --mdb-datepicker-previous-button-after-border-width: 2px;
        --mdb-datepicker-previous-button-after-transform: translateX(2px) rotate(-45deg);
        --mdb-datepicker-next-button-width: 40px;
        --mdb-datepicker-next-button-height: 40px;
        --mdb-datepicker-next-button-line-height: 40px;
        --mdb-datepicker-next-button-color: var(--mdb-surface-color);
        --mdb-datepicker-next-button-disabled-color: rgba(var(--mdb-surface-color-rgb), 0.5);
        --mdb-datepicker-next-button-margin-background-color: var(--mdb-highlight-bg-color);
        --mdb-datepicker-next-button-state-border-radius: 50%;
        --mdb-datepicker-next-button-after-margin: 15.5px;
        --mdb-datepicker-next-button-after-border-width: 2px;
        --mdb-datepicker-next-button-after-transform: translateX(-2px) rotate(45deg);
        --mdb-datepicker-view-padding-x: 12px;
        --mdb-datepicker-table-width: 304px;
        --mdb-datepicker-day-heading-width: 40px;
        --mdb-datepicker-day-heading-height: 40px;
        --mdb-datepicker-day-heading-font-size: 12px;
        --mdb-datepicker-day-heading-font-weight: 400;
        --mdb-datepicker-day-heading-color: var(--mdb-surface-color);
        --mdb-datepicker-cell-disabled-color: rgba(var(--mdb-surface-color-rgb), 0.5);
        --mdb-datepicker-cell-hover-background-color: var(--mdb-highlight-bg-color);
        --mdb-datepicker-cell-selected-background-color: #3b71ca;
        --mdb-datepicker-cell-selected-color: #fff;
        --mdb-datepicker-cell-focused-background-color: var(--mdb-highlight-bg-color);
        --mdb-datepicker-cell-focused-selected-background-color: #3b71ca;
        --mdb-datepicker-cell-border-width: 1px;
        --mdb-datepicker-cell-border-color: var(--mdb-surface-color);
        --mdb-datepicker-cell-color: var(--mdb-surface-color);
        --mdb-datepicker-small-cell-width: 40px;
        --mdb-datepicker-small-cell-height: 40px;
        --mdb-datepicker-small-cell-content-width: 40px;
        --mdb-datepicker-small-cell-content-height: 40px;
        --mdb-datepicker-small-cell-content-line-height: 40px;
        --mdb-datepicker-small-cell-content-border-radius: 50%;
        --mdb-datepicker-small-cell-content-font-size: 13px;
        --mdb-datepicker-large-cell-width: 76px;
        --mdb-datepicker-large-cell-height: 42px;
        --mdb-datepicker-large-cell-content-width: 72px;
        --mdb-datepicker-large-cell-content-height: 40px;
        --mdb-datepicker-large-cell-content-line-height: 40px;
        --mdb-datepicker-large-cell-content-padding-y: 1px;
        --mdb-datepicker-large-cell-content-padding-x: 2px;
        --mdb-datepicker-large-cell-content-border-radius: 999px;
        position: relative;
        height: 100%
    }

    .datepicker-date-controls {
        padding: var(--mdb-datepicker-date-controls-padding-top) var(--mdb-datepicker-date-controls-padding-x) 0 var(--mdb-datepicker-date-controls-padding-x);
        display: flex;
        justify-content: space-between;
        color: var(--mdb-datepicker-date-controls-color)
    }

    .datepicker-view-change-button {
        padding: var(--mdb-datepicker-view-change-button-padding);
        color: var(--mdb-datepicker-view-change-button-color);
        font-weight: var(--mdb-datepicker-view-change-button-font-weight);
        font-size: var(--mdb-datepicker-view-change-button-font-size);
        border-radius: var(--mdb-datepicker-view-change-button-border-radius);
        box-shadow: none;
        background-color: rgba(0, 0, 0, 0);
        margin: 0;
        border: none;
        outline: none
    }

    .datepicker-view-change-button:hover,
    .datepicker-view-change-button:focus {
        background-color: var(--mdb-datepicker-view-change-button-state-background-color)
    }

    .datepicker-view-change-button:after {
        content: "";
        display: inline-block;
        width: 0;
        height: 0;
        border-left: var(--mdb-datepicker-view-change-button-after-border-width) solid rgba(0, 0, 0, 0);
        border-right: var(--mdb-datepicker-view-change-button-after-border-width) solid rgba(0, 0, 0, 0);
        border-top-width: var(--mdb-datepicker-view-change-button-after-border-width);
        border-top-style: solid;
        margin: 0 0 0 var(--mdb-datepicker-view-change-button-after-margin-left);
        vertical-align: middle
    }

    .datepicker-view-change-button.disabled {
        color: var(--mdb-datepicker-view-change-button-disabled-color)
    }

    .datepicker-arrow-controls {
        margin-top: var(--mdb-datepicker-arrow-controls-margin-top)
    }

    .datepicker-previous-button {
        position: relative;
        padding: 0;
        width: var(--mdb-datepicker-previous-button-width);
        height: var(--mdb-datepicker-previous-button-height);
        line-height: var(--mdb-datepicker-previous-button-line-height);
        border: none;
        outline: none;
        margin: 0;
        color: var(--mdb-datepicker-previous-button-color);
        background-color: rgba(0, 0, 0, 0);
        margin-right: var(--mdb-datepicker-previous-button-margin-right)
    }

    .datepicker-previous-button:hover,
    .datepicker-previous-button:focus {
        background-color: var(--mdb-datepicker-previous-button-state-background-color);
        border-radius: var(--mdb-datepicker-previous-button-state-border-radius)
    }

    .datepicker-previous-button.disabled {
        color: var(--mdb-datepicker-previous-button-disabled-color)
    }

    .datepicker-previous-button::after {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        position: absolute;
        content: "";
        margin: var(--mdb-datepicker-previous-button-after-margin);
        border: 0 solid currentColor;
        border-top-width: var(--mdb-datepicker-previous-button-after-border-width);
        border-left-width: var(--mdb-datepicker-previous-button-after-border-width);
        transform: var(--mdb-datepicker-previous-button-after-transform)
    }

    .datepicker-next-button {
        position: relative;
        padding: 0;
        width: var(--mdb-datepicker-next-button-width);
        height: var(--mdb-datepicker-next-button-height);
        line-height: var(--mdb-datepicker-next-button-line-height);
        border: none;
        outline: none;
        margin: 0;
        color: var(--mdb-datepicker-next-button-color);
        background-color: rgba(0, 0, 0, 0)
    }

    .datepicker-next-button:hover,
    .datepicker-next-button:focus {
        background-color: var(--mdb-datepicker-next-button-margin-background-color);
        border-radius: var(--mdb-datepicker-next-button-state-border-radius)
    }

    .datepicker-next-button.disabled {
        color: var(--mdb-datepicker-next-button-disabled-color)
    }

    .datepicker-next-button::after {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        position: absolute;
        content: "";
        margin: var(--mdb-datepicker-next-button-after-margin);
        border: 0 solid currentColor;
        border-top-width: var(--mdb-datepicker-next-button-after-border-width);
        border-right-width: var(--mdb-datepicker-next-button-after-border-width);
        transform: var(--mdb-datepicker-next-button-after-transform)
    }


    .datepicker-view {
        padding-left: var(--mdb-datepicker-view-padding-x);
        padding-right: var(--mdb-datepicker-view-padding-x);
        outline: none
    }

    .datepicker-table {
        margin-right: auto;
        margin-left: auto;
        width: var(--mdb-datepicker-table-width)
    }

    .datepicker-day-heading {
        width: var(--mdb-datepicker-day-heading-width);
        height: var(--mdb-datepicker-day-heading-height);
        text-align: center;
        font-size: var(--mdb-datepicker-day-heading-font-size);
        font-weight: var(--mdb-datepicker-day-heading-font-weight);
        color: var(--prefixdatepicker-day-heading-color)
    }

    .datepicker-cell {
        text-align: center;
        color: var(--mdb-datepicker-cell-color)
    }

    .datepicker-cell.disabled {
        color: var(--mdb-datepicker-cell-disabled-color);
        cursor: default;
        pointer-events: none
    }

    .datepicker-cell.disabled:hover {
        cursor: default
    }

    .datepicker-cell:hover {
        cursor: pointer
    }

    .datepicker-cell:not(.disabled):not(.selected):hover .datepicker-cell-content {
        background-color: var(--mdb-datepicker-cell-hover-background-color)
    }

    .datepicker-cell.selected .datepicker-cell-content {
        background-color: var(--mdb-datepicker-cell-selected-background-color);
        color: var(--mdb-datepicker-cell-selected-color)
    }

    .datepicker-cell:not(.selected).focused .datepicker-cell-content {
        background-color: var(--mdb-datepicker-cell-focused-background-color)
    }

    .datepicker-cell.focused .datepicker-cell-content.selected {
        background-color: var(--mdb-datepicker-cell-focused-selected-background-color)
    }

    .datepicker-cell.current .datepicker-cell-content {
        border: var(--mdb-datepicker-cell-border-width) solid var(--mdb-datepicker-cell-border-color)
    }

    .datepicker-small-cell {
        width: var(--mdb-datepicker-small-cell-width);
        height: var(--mdb-datepicker-small-cell-height)
    }

    .datepicker-small-cell-content {
        width: var(--mdb-datepicker-small-cell-content-width);
        height: var(--mdb-datepicker-small-cell-content-height);
        line-height: var(--mdb-datepicker-small-cell-content-line-height);
        border-radius: var(--mdb-datepicker-small-cell-content-border-radius);
        font-size: var(--mdb-datepicker-small-cell-content-font-size)
    }

    .datepicker-large-cell {
        width: var(--mdb-datepicker-large-cell-width);
        height: var(--mdb-datepicker-large-cell-height)
    }

    .datepicker-large-cell-content {
        width: var(--mdb-datepicker-large-cell-content-width);
        height: var(--mdb-datepicker-large-cell-content-height);
        line-height: var(--mdb-datepicker-large-cell-content-line-height);
        padding: var(--mdb-datepicker-large-cell-content-padding-y) var(--mdb-datepicker-large-cell-content-padding-x);
        border-radius: var(--mdb-datepicker-large-cell-content-border-radius)
    }

    .datepicker-footer {
        height: var(--mdb-datepicker-footer-height);
        display: flex;
        position: absolute;
        width: 100%;
        bottom: 0;
        justify-content: flex-end;
        align-items: center;
        padding-left: var(--mdb-datepicker-footer-padding-x);
        padding-right: var(--mdb-datepicker-footer-padding-x)
    }

    .datepicker-footer-btn {
        background-color: var(--mdb-datepicker-footer-btn-background-color);
        color: var(--mdb-datepicker-footer-btn-color);
        border: none;
        cursor: pointer;
        padding: 0 var(--mdb-datepicker-footer-btn-padding-x);
        text-transform: uppercase;
        font-size: var(--mdb-datepicker-footer-btn-font-size);
        font-weight: var(--mdb-datepicker-footer-btn-font-weight);
        height: var(--mdb-datepicker-footer-btn-height);
        line-height: var(--mdb-datepicker-footer-btn-line-height);
        letter-spacing: var(--mdb-datepicker-footer-btn-letter-spacing);
        border-radius: var(--mdb-datepicker-footer-btn-border-radius);
        margin-bottom: var(--mdb-datepicker-footer-btn-margin-bottom);
        outline: none
    }

    .datepicker-footer-btn:hover,
    .datepicker-footer-btn:focus {
        background-color: var(--mdb-datepicker-footer-btn-state-background-color)
    }

    .datepicker-footer-btn.disabled {
        color: var(--mdb-datepicker-footer-btn-disabled-color)
    }

    .datepicker-clear-btn {
        margin-right: auto
    }

    .animation {
        --mdb-animation-delay-1s: 1s;
        --mdb-animation-delay-2s: 3s;
        --mdb-animation-delay-3s: 3s;
        --mdb-animation-delay-4s: 4s;
        --mdb-animation-delay-5s: 5s;
        --mdb-animation-fast-duration: 800ms;
        --mdb-animation-faster-duration: 500ms;
        --mdb-animation-slow-duration: 2s;
        --mdb-animation-slower-duration: 3s
    }





    /* styles.css */

    .date-picker {
        position: relative;
        display: inline-block;
    }

    #date-input {
        width: 200px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
    }

    .calendar {
        display: none;
        position: absolute;
        top: 40px;
        left: 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        z-index: 1000;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f0f0f0;
        border-bottom: 1px solid #ccc;
    }

    .calendar-header button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    .calendar-header button:hover {
        background-color: #0056b3;
    }

    .calendar table {
        width: 100%;
        border-collapse: collapse;
    }

    .calendar th,
    .calendar td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ccc;
        cursor: pointer;
    }

    .calendar td:hover {
        background-color: #e0e0e0;
    }
</style>
@endsection

@section('content')

<div class="date-picker">
    <input type="text" id="date-input" placeholder="Selecciona una fecha" readonly>
    <div class="calendar" id="calendar">
        <div class="calendar-header">
            <button id="prev-month">Anterior</button>
            <span id="current-month-year"></span>
            <button id="next-month">Siguiente</button>
            <button id="select-year">Seleccionar Año</button>
        </div>
        <div id="calendar-body"></div>
    </div>
</div>

<!-- <div class="datepicker-backdrop animation fade-out" style="animation-duration: 150ms;"></div> -->
<div class="datepicker-modal-container datepicker-modal-container-datepicker-toggle-475564 animation fade-out" style="animation-duration: 300ms;">

    <div class="datepicker-header">

        <div class="datepicker-title">
            <span class="datepicker-title-text">Select date</span>
        </div>
        <div class="datepicker-date">
            <span class="datepicker-date-text">Fri, Jul 26</span>
        </div>

    </div>


    <div class="datepicker-main">

        <div class="datepicker-date-controls">
            <button class="datepicker-view-change-button" aria-label="Switch to year list">July 2024</button>
            <div class="datepicker-arrow-controls">
                <button class="datepicker-previous-button" aria-label="Previous month"></button>
                <button class="datepicker-next-button" aria-label="Next month"></button>
            </div>
        </div>

        <div class="datepicker-view" tabindex="0">

            <table class="datepicker-table">
                <thead>

                    <tr>
                        <th class="datepicker-day-heading" scope="col" aria-label="Sunday">S</th>
                        <th class="datepicker-day-heading" scope="col" aria-label="Monday">M</th>
                        <th class="datepicker-day-heading" scope="col" aria-label="Tuesday">T</th>
                        <th class="datepicker-day-heading" scope="col" aria-label="Wednesday">W</th>
                        <th class="datepicker-day-heading" scope="col" aria-label="Thursday">T</th>
                        <th class="datepicker-day-heading" scope="col" aria-label="Friday">F</th>
                        <th class="datepicker-day-heading" scope="col" aria-label="Saturday">S</th>
                    </tr>

                </thead>
                <tbody class="datepicker-table-body">

                    <tr>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-5-30" aria-label="Sunday, June 30, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                30
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-1" aria-label="Monday, July 1, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                1
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-2" aria-label="Tuesday, July 2, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                2
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-3" aria-label="Wednesday, July 3, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                3
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-4" aria-label="Thursday, July 4, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                4
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-5" aria-label="Friday, July 5, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                5
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-6" aria-label="Saturday, July 6, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                6
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-7" aria-label="Sunday, July 7, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                7
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-8" aria-label="Monday, July 8, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                8
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-9" aria-label="Tuesday, July 9, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                9
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-10" aria-label="Wednesday, July 10, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                10
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-11" aria-label="Thursday, July 11, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                11
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-12" aria-label="Friday, July 12, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                12
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-13" aria-label="Saturday, July 13, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                13
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-14" aria-label="Sunday, July 14, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                14
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-15" aria-label="Monday, July 15, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                15
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-16" aria-label="Tuesday, July 16, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                16
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-17" aria-label="Wednesday, July 17, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                17
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-18" aria-label="Thursday, July 18, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                18
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-19" aria-label="Friday, July 19, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                19
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-20" aria-label="Saturday, July 20, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                20
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-21" aria-label="Sunday, July 21, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                21
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-22" aria-label="Monday, July 22, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                22
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-23" aria-label="Tuesday, July 23, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                23
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-24" aria-label="Wednesday, July 24, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                24
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-25" aria-label="Thursday, July 25, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                25
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell current null" data-mdb-date="2024-6-26" aria-label="Friday, July 26, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                26
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-27" aria-label="Saturday, July 27, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                27
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-28" aria-label="Sunday, July 28, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                28
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-29" aria-label="Monday, July 29, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                29
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-30" aria-label="Tuesday, July 30, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                30
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
               
              false null" data-mdb-date="2024-6-31" aria-label="Wednesday, July 31, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: block">
                                31
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-1" aria-label="Thursday, August 1, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                1
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-2" aria-label="Friday, August 2, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                2
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-3" aria-label="Saturday, August 3, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                3
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-4" aria-label="Sunday, August 4, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                4
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-5" aria-label="Monday, August 5, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                5
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-6" aria-label="Tuesday, August 6, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                6
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-7" aria-label="Wednesday, August 7, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                7
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-8" aria-label="Thursday, August 8, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                8
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-9" aria-label="Friday, August 9, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                9
                            </div>
                        </td>

                        <td class="datepicker-cell datepicker-small-cell datepicker-day-cell
              disabled 
              false null" data-mdb-date="2024-7-10" aria-label="Saturday, August 10, 2024" aria-selected="null">
                            <div class="datepicker-cell-content datepicker-small-cell-content" style="display: none">
                                10
                            </div>
                        </td>

                    </tr>

                </tbody>
            </table>

        </div>

        <div class="datepicker-footer">
            <button class="datepicker-footer-btn datepicker-clear-btn" aria-label="Clear selection">Clear</button>
            <button class="datepicker-footer-btn datepicker-cancel-btn" aria-label="Cancel selection">Cancel</button>
            <button class="datepicker-footer-btn datepicker-ok-btn" aria-label="Confirm selection">Ok</button>
        </div>

    </div>

</div>

@endsection

@section('scripts')
<script>
    // script.js

    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date-input');
        const calendar = document.getElementById('calendar');
        const calendarBody = document.getElementById('calendar-body');
        const currentMonthYear = document.getElementById('current-month-year');
        const prevMonthButton = document.getElementById('prev-month');
        const nextMonthButton = document.getElementById('next-month');
        const selectYearButton = document.getElementById('select-year');

        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();
        let selectingYear = false;

        dateInput.addEventListener('click', function() {
            calendar.style.display = calendar.style.display === 'block' ? 'none' : 'block';
        });

        prevMonthButton.addEventListener('click', function() {
            if (selectingYear) {
                currentYear -= 10;
                generateYearSelector(currentYear);
            } else {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentMonth, currentYear);
            }
        });

        nextMonthButton.addEventListener('click', function() {
            if (selectingYear) {
                currentYear += 10;
                generateYearSelector(currentYear);
            } else {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentMonth, currentYear);
            }
        });

        selectYearButton.addEventListener('click', function() {
            selectingYear = !selectingYear;
            if (selectingYear) {
                generateYearSelector(currentYear);
            } else {
                generateCalendar(currentMonth, currentYear);
            }
        });

        function generateCalendar(month, year) {
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay();

            let calendarHtml = '<table>';
            calendarHtml += '<tr><th>Dom</th><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th></tr>';

            let date = 1;
            for (let i = 0; i < 6; i++) {
                calendarHtml += '<tr>';
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        calendarHtml += '<td></td>';
                    } else if (date > daysInMonth) {
                        break;
                    } else {
                        calendarHtml += `<td>${date}</td>`;
                        date++;
                    }
                }
                calendarHtml += '</tr>';
            }

            calendarHtml += '</table>';
            calendarBody.innerHTML = calendarHtml;
            currentMonthYear.textContent = `${monthNames[month]} ${year}`;

            const cells = calendarBody.getElementsByTagName('td');
            for (let cell of cells) {
                if (cell.textContent !== '') {
                    cell.addEventListener('click', function() {
                        dateInput.value = `${cell.textContent}/${month + 1}/${year}`;
                        calendar.style.display = 'none';
                    });
                }
            }
        }

        function generateYearSelector(year) {
            let yearHtml = '<table>';
            yearHtml += '<tr>';
            for (let i = -5; i <= 5; i++) {
                yearHtml += `<td>${year + i}</td>`;
            }
            yearHtml += '</tr>';
            yearHtml += '</table>';
            calendarBody.innerHTML = yearHtml;
            currentMonthYear.textContent = `Seleccionar Año`;

            const cells = calendarBody.getElementsByTagName('td');
            for (let cell of cells) {
                cell.addEventListener('click', function() {
                    currentYear = parseInt(cell.textContent);
                    selectingYear = false;
                    generateCalendar(currentMonth, currentYear);
                });
            }
        }

        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        generateCalendar(currentMonth, currentYear);
    });
</script>
@endsection