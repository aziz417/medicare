import React from 'react';

const Toaster = (props) => {
    return (
        <div className="app-toast border-danger toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div className="bg-danger text-white toast-header" onClick={props.close}>
                <strong className="mr-auto">{props.title||"Alert"}</strong>
                {props.time ? (<small>{props.time}</small>):null}
                <button onClick={props.close} type="button" className="ml-2 mb-1 close" ><span aria-hidden="true">×</span></button>
            </div>
            <div className="toast-body">
                {props.message}
            </div>
        </div>
    )
}

export default Toaster;