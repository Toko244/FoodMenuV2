import React, { useEffect, useRef, useState } from "react";
export default function Editor({ onChange, editorLoaded, name, value }) {
    const editorRef = useRef();
    const [isLoaded, setIsLoaded] = useState(false);
    const [CKEditor, setCKEditor] = useState(null);
    const [ClassicEditor, setClassicEditor] = useState(null);

    useEffect(() => {
        import("@ckeditor/ckeditor5-react").then((module) => {
            setCKEditor(() => module.CKEditor);
        });
        import("@ckeditor/ckeditor5-build-classic").then((module) => {
            setClassicEditor(() => module.default);
        });
        setIsLoaded(true);
    }, []);

    return (
        <div>
            {editorLoaded && isLoaded && CKEditor && ClassicEditor ? (
                <CKEditor
                    name={name}
                    editor={ClassicEditor}
                    config={{
                        ckfinder: {
                            // Upload the images to the server using the CKFinder QuickUpload command
                            // You have to change this address to your server that has the ckfinder php connector
                            uploadUrl: "", // Enter your upload URL
                        },
                    }}
                    data={value}
                    onChange={(event, editor) => {
                        const data = editor.getData();
                        onChange(data);
                    }}
                />
            ) : (
                <div>Editor loading</div>
            )}
        </div>
    );
}
