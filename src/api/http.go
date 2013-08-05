package api

import (
    "../../deps/go.net/websocket"
    "../../deps/lessgo/passport"
    "../conf"
    "fmt"
    "net/http"
    "time"
)

type Api struct {
    Session passport.Session
    Cfg     conf.Config
}

type ApiResponse struct {
    Status  int    `json:"status"`
    Message string `json:"message"`
}

func (this *Api) Serve(port string) {

    fmt.Println("Api.Serve")
    //kpr = data.NewKprHttp()

    go func() {
        //http.Handle("/lesscreator/api", websocket.Handler(QueueStatus))
        http.HandleFunc("/lesscreator/api/user-login", UserLogin)

        http.HandleFunc("/lesscreator/api/fs-list", FsList)
        http.HandleFunc("/lesscreator/api/fs-file-put", FsFilePut)
        http.HandleFunc("/lesscreator/api/fs-file-get", FsFileGet)
        http.HandleFunc("/lesscreator/api/fs-file-new", FsFileNew)
        http.HandleFunc("/lesscreator/api/fs-file-del", FsFileDel)
        http.HandleFunc("/lesscreator/api/fs-file-mov", FsFileMov)
        http.HandleFunc("/lesscreator/api/fs-file-upl", FsFileUpl)
        http.Handle("/lesscreator/api/fs-save-ws", websocket.Handler(FsSaveWS))

        http.HandleFunc("/lesscreator/api/env-init", this.EnvInit)

        s := &http.Server{
            Addr:    ":" + port,
            Handler: nil,
            //ReadTimeout:    30 * time.Second,
            //WriteTimeout:   30 * time.Second,
            //MaxHeaderBytes: 1 << 20,
        }
        s.ListenAndServe()
    }()

    for {
        time.Sleep(1e9)
    }
}

func UserLogin(w http.ResponseWriter, r *http.Request) {

    defer func() {
        r.Body.Close()
    }()

    goto RSP

RSP:
    w.Header().Add("Connection", "close")

    return
}